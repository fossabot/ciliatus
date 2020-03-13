<?php

namespace Ciliatus\Common\Models;

use Ciliatus\Api\Http\Transformers\GenericTransformer;
use Ciliatus\Common\Enum\DatabaseDataTypesEnum;
use Ciliatus\Common\Enum\PropertyTypesEnum;
use Ciliatus\Common\Exceptions\InvalidPropertyDataTypeException;
use Ciliatus\Common\Traits\HasHistoryTrait;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

abstract class Model extends \Illuminate\Database\Eloquent\Model implements ModelInterface
{

    use HasHistoryTrait;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array|null
     */
    protected ?array $transformable = null;

    /**
     * @var string
     */
    protected string $prefix;

    /**
     * @var array
     */
    protected array $transform_ignore = ['icon'];

    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = 'ciliatus_' . strtolower($this->package()) . '__' . $this->getTable();
        parent::__construct($attributes);
    }

    /**
     * @return mixed
     */
    public function model(): string
    {
        $class = explode('\\', get_class($this));
        return end($class);
    }

    /**
     * @return string
     */
    public function package(): string
    {
        $class = explode('\\', get_class($this));
        return $class[count($class)-3];
    }

    /**
     * @return string
     */
    public function fk(): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->model())) . '_id';
    }

    /**
     * @return string
     */
    public function self(): string
    {
        return url('api/v1/' . $this->table . '/' . $this->id);
    }

    /**
     * @return Model
     */
    public function enrich(): Model
    {
        return $this;
    }

    /**
     * @return array
     */
    public function transform(): array
    {
        $transformerClass = "Ciliatus\\Api\\Http\\Transformers\\" . __CLASS__ . "Transformer";
        $transformer = null;
        if (class_exists($transformerClass)) {
            $transformer = new $transformerClass;
        } else {
            $transformer = new GenericTransformer();
        }

        return $transformer($this);
    }

    /**
     * @return array
     */
    public function getTransformable(): array
    {
        $transformable = $this->transformable ?? $this->fillable;
        $ignore = $this->transform_ignore;
        return array_filter($transformable, function ($t) use ($ignore) {
            return !in_array($t, $ignore);
        });

        return $transformable;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon ?? 'mdi-help-circle-outline';
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getRelationNames(): array
    {
        $reflection = new ReflectionClass(static::class);
        $relations = [];
        foreach ($reflection->getMethods() as $method) {
            $return_type = $method->getReturnType();
            if (is_null($return_type)) {
                continue;
            }

            $namespace_parts = explode('\\', $return_type->getName());
            $class_name = $namespace_parts[count($namespace_parts)-1];
            unset($namespace_parts[count($namespace_parts)-1]);
            $namespace = implode('\\', $namespace_parts);

            if ($namespace == 'Illuminate\\Database\\Eloquent\\Relations') {
                $relations[$method->getName()] = $class_name;
            }
        };

        return $relations;
    }

    /**
     * @return MorphMany
     */
    public function properties(): MorphMany
    {
        return $this->morphMany(Property::class, 'belongsToModel');
    }

    /**
     * @param PropertyTypesEnum $type
     * @return Collection
     */
    public function getProperties(PropertyTypesEnum $type): Collection
    {
        return $this->properties()->where('type', $type)->get();
    }

    /**
     * @param PropertyTypesEnum $type
     * @param string $name
     * @param bool $create
     * @param DatabaseDataTypesEnum $create_datatype
     * @return Property
     */
    public function getProperty(PropertyTypesEnum $type, string $name, $create = false, DatabaseDataTypesEnum $create_datatype = null): ?Property
    {
        /** @var Property $property */
        $property = $this->properties()->where('type', $type)->where('name', $name)->first();
        if (is_null($property) && $create) {
            $create_datatype = $create_datatype ?? DatabaseDataTypesEnum::DATATYPE_STRING();
            $property = $this->properties()->create([
                'type' => $type,
                'name' => $name,
                'datatype' => $create_datatype
            ]);
        }

        return $property;
    }

    /**
     * @param PropertyTypesEnum $type
     * @param string $name
     * @param mixed $default
     * @return mixed|null
     */
    public function getPropertyValue(PropertyTypesEnum $type, string $name, $default = null)
    {
        $property = $this->getProperty($type, $name);
        if (is_null($property)) return $default;

        return $property->getValue();
    }

    /**
     * @param PropertyTypesEnum $type
     * @param string $name
     * @param mixed $value
     * @param DatabaseDataTypesEnum $datatype
     * @return Property
     * @throws InvalidPropertyDataTypeException
     */
    public function setProperty(PropertyTypesEnum $type, string $name, $value, DatabaseDataTypesEnum $datatype = null): Property
    {
        $datatype = $datatype ?? $this->getPropertyValueTypeByValue($value);
        $value_field = 'value_' . $datatype;

        $property = $this->getProperty($type, $name, true, $datatype)->fill([
            $value_field => $value,
            'datatype' => $datatype,
            'is_active' => true
        ]);

        $property->save();

        return $property;
    }

    /**
     * @param PropertyTypesEnum $type
     * @throws Exception
     */
    public function deleteProperties(PropertyTypesEnum $type): void
    {
        $this->getProperties($type)->each(function (Property $property) {
            $property->delete();
        });
    }

    /**
     * @param PropertyTypesEnum $type
     * @param string $name
     * @throws Exception
     */
    public function deleteProperty(PropertyTypesEnum $type, string $name): void
    {
        if (!is_null($p = $this->getProperty($type, $name))) {
            $p->delete();
        }
    }

    /**
     * @param mixed $value
     * @return string
     * @throws InvalidPropertyDataTypeException
     */
    public function getPropertyValueTypeByValue($value): string
    {
        $mapping = [
            'boolean' => [ 'boolean' ],
            'float' => [ 'float', 'double' ],
            'bigint' => [ 'integer' ],
            'string' => [ 'string' ]
        ];

        foreach ($mapping as $field=>$types) {
            if (in_array(gettype($value), $types)) {
                return $field;
            }
        }

        throw new InvalidPropertyDataTypeException(gettype($value));
    }

}
