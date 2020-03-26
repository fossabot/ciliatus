<?php

namespace Ciliatus\Api\Http\Controllers;

use Ciliatus\Api\Exceptions\MissingRequestFieldException;
use Ciliatus\Api\Exceptions\UnhandleableRelationshipException;
use Ciliatus\Api\Http\Controllers\Actions\StoreAction;
use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Common\Enum\HttpStatusCodeEnum;
use Ciliatus\Common\Models\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Matthenning\EloquentApiFilter\Traits\FiltersEloquentApi;
use ReflectionClass;
use ReflectionException;

class Controller extends \App\Http\Controllers\Controller implements ControllerInterface
{

    use FiltersEloquentApi;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var
     */
    protected array $meta = [];

    /**
     * @var string
     */
    protected string $package = 'Api';

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (!$this->request->allows()) {
            return $this->respondUnauthorized();
        }

        $model_class_name = $this->getModelName();
        $query = $model_class_name::query();

        if ($this->request->has('all')) {
            return $this->respondWithModels($query->get());
        }

        return $this->respondFilteredAndPaginated($query);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if (!$this->request->allows()) {
            return $this->respondUnauthorized();
        }

        $model_class_name = $this->getModelName();
        $query = $model_class_name::where('id', $id);

        return $this->respondFiltered($query);
    }

    /**
     * @param Request $request
     * @param mixed $except
     * @param callable $pre Function to execute before model creation. Parameters: Request $request
     * @param callable $post Function to execute after model creation. Parameters: mixed $result_of_pre_function, Model $created_model, Request $request
     * @return JsonResponse
     * @throws MissingRequestFieldException
     * @throws UnhandleableRelationshipException
     * @throws ReflectionException
     */
    public function _store(Request $request, $except = [], callable $pre = null, callable $post = null)
    {
        if (!$this->request->allows()) {
            return $this->respondUnauthorized();
        }

        $pre_result = $pre ? $pre($request) : null;

        $model = StoreAction::prepare($request, $this->getModelName())->except($except)->auto()->invoke();

        if ($post) $post($pre_result, $model, $request);

        return $this->respondWithModel($model->fresh());
    }

    /**
     * @param Builder $query
     * @return JsonResponse
     */
    protected function respondFiltered(Builder $query): JsonResponse
    {
        $results = $this->filterApiRequest($this->request, $query)->get();

        if ($results->isEmpty()) {
            return $this->respondNotFound();
        }

        return $this->respondWithModels($results);
    }

    /**
     * @param Builder $query
     * @return JsonResponse
     */
    protected function respondFilteredAndPaginated(Builder $query): JsonResponse
    {
        $query = $this->filterApiRequest($this->request, $query);
        $results = $this->paginateModels($query);

        return $this->respondWithPaginatedModels($results);
    }

    /**
     * @param Builder $query
     * @return LengthAwarePaginator
     */
    public function paginateModels(Builder $query): LengthAwarePaginator
    {
        if ($this->request->has('per_page')) {
            $perPage = ($pp = $this->request->get('per_page')) == -1 ? $query->count() : $pp;
        } else {
            $perPage = config('pagination_per_page');
        }

        return $query->paginate($perPage);
    }

    /**
     * @param Model $model
     * @return JsonResponse
     */
    public function respondWithModel(Model $model): JsonResponse
    {
        return $this->respondWithModels(new Collection([$model]));
    }

    /**
     * @param array $model_transformed
     * @return Collection
     */
    protected function getSeparateModelsFromRelations(array $model_transformed): Collection
    {
        $models = new Collection();
        foreach ($model_transformed['relations'] as $name=>$related_models) {
            if (is_null($related_models)) continue;

            if (count($related_models) > 0 && !isset($related_models['_model'])) {
                // Has Many related models -> $related_models contains an array of models
                foreach ($related_models as $m) {
                    if (isset($m['relations']) && count($m['relations']) > 0) {
                        $models = $models->merge($this->getSeparateModelsFromRelations($m));
                    }

                    $models->add($m);
                    
                    $pivot_model = strcmp($model_transformed['_model'], $m['_model']) < 0 ?
                        $model_transformed['_model'] . $m['_model'] :
                        $m['_model'] . $model_transformed['_model'];
                    $pivot_model .= 'Pivot';

                    if ($model_transformed['_relations'][$name] == 'BelongsToMany') {
                        // Many to Many relation -> we need to generate intermediate Models
                        $models->add([
                            '_model' => $pivot_model,
                            '_meta' => ['is_virtual' => true],
                            'id' => '_pivot-' . $model_transformed['id'] . '-' . $m['_model'] . '-' . $m['id'],
                            $model_transformed['_fk'] => $model_transformed['id'],
                            $m['_fk'] => $m['id']
                        ]);
                    } else if ($model_transformed['_relations'][$name] == 'MorphToMany') {
                        // Many to Many Polymorphic relation -> we need to generate intermediate Models
                        $models->add([
                            '_model' => $pivot_model,
                            '_meta' => ['is_virtual' => true],
                            'id' => '_pivot-' . $model_transformed['id'] . '-' . $m['_model'] . '-' . $m['id'],
                            $m['_fk'] => $m['id'],
                            'belongsToModel_type' => $model_transformed['_model'],
                            'belongsToModel_id' => $model_transformed['id']
                        ]);
                    }
                }

            } else {
                // One to one relation -> $related_models contains a single model
                $related_model = $related_models;
                if (isset($related_model['relations']) && count($related_model['relations']) > 0) {
                    $models = $models->merge($this->getSeparateModelsFromRelations($related_model));
                }

                $models->add($related_model);
            }
        }

        return $models->map(function (array $model) {
            if (isset($model['relations']))
                unset($model['relations']);
            return $model;
        });
    }

    /**
     * @param Collection $models
     * @return JsonResponse
     */
    public function respondWithModels(Collection $models): JsonResponse
    {
        $transformed = $models->map(function (Model $model) { return $model->enrich()->transform(); });

        if ($this->request->get('relations') == 'separate') {
            foreach ($transformed as $t) {
                if (!isset($t['relations'])) continue;
                $transformed = $transformed->merge($this->getSeparateModelsFromRelations($t));

                $transformed = $transformed->map(function (array $model) {
                    unset($model['relations']);
                    return $model;
                });
            }
        }

        $transformed = $transformed->filter();

        $transformed = $transformed->unique(function ($item) {
            return $item['_model'].$item['id'];
        });

        return $this->respondWithData(array_values($transformed->toArray()));
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @return JsonResponse
     */
    public function respondWithPaginatedModels(LengthAwarePaginator $paginator): JsonResponse
    {
        $items = new Collection($paginator->items());

        $this->meta['pagination'] = [
            'items'         => $items->count(),
            'total_items'   => $paginator->total(),
            'total_pages'   => $paginator->lastPage(),
            'current_page'  => $paginator->currentPage(),
            'per_page'      => $paginator->perPage()
        ];

        if ($this->request->has('pagination')) {
            return $this->respondWithData([]);
        }

        return $this->respondWithModels($items);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function respondWithData(array $data): JsonResponse
    {
        return $this->respond([
            'meta' => $this->meta,
            'data' => $data
        ]);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotFound(string $message = 'Model not found'): JsonResponse
    {
        return $this->respondWithError($message, HttpStatusCodeEnum::Not_Found());
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respondWithError($message, HttpStatusCodeEnum::Forbidden());
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondUnauthenticated(string $message = 'Unauthenticated'): JsonResponse
    {
        return $this->respondWithError($message, HttpStatusCodeEnum::Unauthorized());
    }

    /**
     * @param array $errors
     * @param HttpStatusCodeEnum $status
     * @return JsonResponse
     */
    public function respondWithErrors(array $errors, HttpStatusCodeEnum $status): JsonResponse
    {
        return $this->respond(['errors' => $errors], $status);
    }

    /**
     * @param string $error
     * @param HttpStatusCodeEnum $status
     * @return JsonResponse
     */
    public function respondWithError(string $error, HttpStatusCodeEnum $status): JsonResponse
    {
        return $this->respondWithErrors([$error], $status);
    }

    /**
     * @param array $data
     * @param HttpStatusCodeEnum $status
     * @return JsonResponse
     */
    public function respond(array $data, HttpStatusCodeEnum $status = null): JsonResponse
    {
        $status = $status ?? HttpStatusCodeEnum::OK();
        return response()->json($data)->setStatusCode($status->getValue());
    }

    /**
     * @return string
     */
    protected function getModelName(): string
    {
        $model_class_name_arr = explode('\\', get_class($this));
        preg_match_all('/((?:^|[A-Z])[a-z]+)/',end($model_class_name_arr),$matches, PREG_PATTERN_ORDER);
        $class_name = implode(
            '',
            array_slice(
                $matches[0],
                0,
                count($matches[0]) - 1
            )
        );

        return 'Ciliatus\\' . $this->package . '\\Models\\' . $class_name;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function customActions(): array
    {
        $mapping = [];
        $reflection = new ReflectionClass(get_called_class());
        foreach ($reflection->getMethods() as $method) {
            if (count($split = explode('__', $method->getName())) == 2) {
                if (strlen($split[0]) < 1 ) continue;
                $mapping[$split[0]] = $split[1];
            }
        }

        return $mapping;
    }
}