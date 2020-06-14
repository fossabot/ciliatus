<?php

namespace Ciliatus\Api\Http\Controllers\Actions;

use Ciliatus\Api\Exceptions\MissingRequestFieldException;
use Ciliatus\Api\Exceptions\UnhandleableRelationshipException;
use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Common\Models\Model;
use Illuminate\Support\Collection;
use ReflectionException;

class UpdateAction extends Action
{

    /**
     * @var int
     */
    protected int $id;

    /**
     * @param Request $request
     * @param string $model_name
     * @param int $id
     * @return static
     */
    public static function prepare(Request $request, string $model_name, int $id): self
    {
        $action = new self($request, $model_name);
        $action->setId($id);

        return $action;
    }

    /**
     * @return Model
     * @throws MissingRequestFieldException
     * @throws ReflectionException
     * @throws UnhandleableRelationshipException
     */
    public function invoke(): Model
    {
        $this->updateModel()->updateRelations();

        $this->model->save();

        return $this->model;
    }

    /**
     * @return Model
     * @throws MissingRequestFieldException
     * @throws ReflectionException
     * @throws UnhandleableRelationshipException
     */
    public function __invoke()
    {
        return $this->invoke();
    }

    /**
     * @return $this
     * @throws MissingRequestFieldException
     */
    protected function updateModel(): self
    {
        $this->model = $this->model_name::find($this->id);

        foreach ($this->fields as $field) {
            if ($this->strict && !$this->request->has($field)) {
                throw new MissingRequestFieldException($field);
            }

            $this->model->$field = $this->request->get($field);
        }

        if ($this->auto) {
            foreach ((new $this->model_name)->getFillable() as $fillable) {
                if ($this->fields->contains($fillable)) continue;

                $field = preg_replace('/_id$/', '', $fillable);
                if ($this->relations->has($field)) {
                    $this->model->$fillable = $this->relations->get($field);
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws ReflectionException
     * @throws UnhandleableRelationshipException
     */
    protected function updateRelations(): self
    {
        if (!$this->relations) return $this;

        // Remove related models which were not present in the update query at all
        foreach ($this->model::getRelationNames() as $name=>$relation) {
            if ($this->relations->contains($name)) {
                switch ($relation) {
                    case 'BelongsTo':
                    case 'MorphTo':
                        $this->model->$name()->dissociate();
                        break;
                    case 'BelongsToMany':
                    case 'MorphToMany':
                    case 'MorphedByMany':
                    case 'HasOne':
                    case 'HasMany':
                    case 'MorphOne':
                    case 'MorphMany':
                        $this->model->$name()->detach();
                        break;
                    default:
                        throw new UnhandleableRelationshipException($relation);
                }
            }
        }

        // Sync relations from query
        foreach ($this->relations as $name=>$ids) {
            $relation = $this->model::getRelationNames()[$name];
            $model_name = $this->model->$name()->getRelated();
            $models = $model_name::findMany($ids);

            switch ($relation) {
                case 'BelongsTo':
                case 'MorphTo':
                    $this->model->$name()->associate($ids);
                    break;
                case 'BelongsToMany':
                case 'MorphToMany':
                case 'MorphedByMany':
                    $this->model->$name()->detach();
                    $this->model->$name()->attach($ids);
                    break;
                case 'HasOne':
                case 'HasMany':
                case 'MorphOne':
                case 'MorphMany':
                    // Retrieve all models we need to "detach" and unset their foreign key
                    // @TODO: There has to be a better method than this :/
                    $related_ids = $this->model->$name()->select('id')->get();
                    if (!is_a($related_ids, Collection::class)) $related_ids = collect($related_ids);
                    $ids_to_remove = $related_ids->filter(fn($r) => !in_array($r->id, $ids))->pluck('id');
                    $fk = $this->model->$name()->getForeignKeyName();
                    foreach ($ids_to_remove as $id_to_remove) {
                        $foreign_model = $model_name::find($id_to_remove);
                        $foreign_model->$fk = null;
                        $foreign_model->save();
                    }

                    $this->model->$name()->saveMany($models);
                    break;
                default:
                    throw new UnhandleableRelationshipException($relation);
            }
        }

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

}