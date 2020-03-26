<?php

namespace Ciliatus\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class Request extends FormRequest implements RequestInterface
{

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * @return Collection
     */
    public function valid(): Collection
    {

        return collect(parent::validated());
    }
    /**
     * @return array
     */
    protected function getRouteInfo(): array
    {
        preg_match(
            "/Ciliatus\\\([A-Za-z]+)\\\Http\\\Controllers\\\([A-Za-z]+)Controller@([A-Za-z_\-]+)/",
            Route::current()->action['controller'],
            $matches
        );

        if (strpos($matches[3], '__') != false) $matches[3] = explode('__', $matches[3])[0];
        return array_slice($matches, 1, 3);
    }

    /**
     * @return string
     */
    protected function getController(): string
    {
        return explode('@', Route::current()->action['controller'])[0];
    }

    /**
     * Maps custom controller methods to the respective permissions and merges them with default permissions
     */
    protected function permissionMapping(): array
    {
        $permission_mapping = $this->defaultPermissionMapping();

        $customAction = self::getController()::customActions();
        foreach ($customAction as $method=>$action) {
            $this->permission_mapping[$method] = $this->permission_mapping[$action];
        }

        return $permission_mapping;
    }

    /**
     * Returns the default mapping of controller methods to permissions.
     *
     * @return array
     */
    protected function defaultPermissionMapping(): array
    {
        return [
            'index'  => 'read',
            'show'   => 'read',
            'store'  => 'write',
            'update' => 'write',
            'delete' => 'write'
        ];
    }

    protected function gate(): string
    {
        list($package, $model, $action) = $this->getRouteInfo();
        return $this->permissionMapping()[$action] . '-' . strtolower($package);
    }

    public function allows(): bool
    {
        return Gate::allows($this->gate());
    }

}