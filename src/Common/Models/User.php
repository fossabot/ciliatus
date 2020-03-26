<?php

namespace Ciliatus\Common\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \App\User
{
    use HasApiTokens, Notifiable;

    /**
     * @return array
     */
    public function transform(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * @param string $package
     * @param string $action
     * @return bool
     */
    public function hasPermission(string $package, string $action): bool
    {
        $check_permission = function (Builder $query) use ($action) {
            return $query->where('action', $action)->orWhere('action', 'admin');
        };

        return $this->permissions()->where('package', $package)->where($check_permission)->exists();
    }

    /**
     * @param string $package
     * @param string $action
     * @return $this
     */
    public function grantPermission(string $package, string $action): self
    {
        if (!$this->hasPermission($package, $action)) {
            $this->permissions()->create([
                'package' => $package,
                'action' => $action
            ]);
        }

        return $this;
    }

    /**
     * @param string $package
     * @param string $action
     * @return $this
     */
    public function revokePermission(string $package, string $action): self
    {
        $this->permissions()->where('package', $package)->where('action', $action)->delete();

        return $this;
    }

    /**
     * @param array $permissions Format: [ 'package' => [ 'action', ... ] ]
     * @return $this
     */
    public function syncPermissions(array $permissions): self
    {
        $this->permissions()->delete();
        foreach ($permissions as $package=>$actions) {
            foreach ($actions as $action) {
                $this->grantPermission($package, $action);
            }
        }

        return $this;
    }
}