<?php

namespace Ciliatus\Api\Http\Controllers\Actions;

use Ciliatus\Api\Exceptions\MissingRequestFieldException;
use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Common\Models\Model;
use Illuminate\Support\Collection;

class StoreAction extends Action
{

    /**
     * @var Collection
     */
    protected Collection $request;

    /**
     * @var Collection
     */
    protected Collection $fields;

    /**
     * @var Collection|mixed
     */
    protected Collection $relations;

    /**
     * @var string
     */
    protected string $model_name;

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var bool
     */
    protected bool $strict = false;

    /**
     * @var bool
     */
    protected bool $auto = false;

    /**
     * StoreAction constructor.
     * @param Request $request
     * @param string $model_name
     */
    public function __construct(Request $request, string $model_name)
    {
        $this->request = $request->valid();
        $this->model_name = $model_name;

        $this->fields = $this->request->keys()->filter(fn($k) => $k != 'relations');
        $this->relations = collect($this->request->get('relations'));
    }


    /**
     * @param Request $request
     * @param string $model_name
     * @return static
     */
    public static function prepare(Request $request, string $model_name): self
    {
        return new self($request, $model_name);
    }

    /**
     * Override automatically generated field list.
     *
     * @param Collection $fields
     * @return $this
     */
    public function fields(Collection $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Throw Exception if a field from the fields list is not contained in the request.
     *
     * @param bool $strict
     * @return $this
     */
    public function strict($strict = true): self
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * Try to automatically extract fillable fields from relations.
     * Useful e.g. for creating models with a not-null belongsTo relations.
     *
     * @param bool $auto
     * @return $this
     */
    public function auto($auto = true): self
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * @return Model
     * @throws MissingRequestFieldException
     */
    public function invoke(): Model
    {
        $this->createModel()->attachRelations();

        return $this->model;
    }

    /**
     * @return $this
     * @throws MissingRequestFieldException
     */
    protected function createModel(): self
    {
        $payload = [];

        foreach ($this->fields as $field) {
            if ($this->strict && !$this->request->has($field)) {
                throw new MissingRequestFieldException($field);
            }

            $payload[$field] = $this->request->get($field);
        }

        if ($this->auto) {
            foreach ((new $this->model_name)->getFillable() as $fillable) {
                if ($this->fields->contains($fillable)) continue;

                $field = preg_replace('/_id$/', '', $fillable);
                if ($this->relations->has($field)) {
                    $payload[$fillable] = $this->relations->get($field);
                }
            }
        }

        $this->model = $this->model_name::create($payload);

        return $this;
    }

    /**
     * @return $this
     */
    protected function attachRelations(): self
    {
        if (!$this->relations) return $this;

        foreach ($this->relations as $name=>$ids) {
            $this->model->$name()->associate($ids);
        }

        return $this;
    }

}