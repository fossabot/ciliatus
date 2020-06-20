<?php

namespace Ciliatus\Core\Http\Controllers;


use Ciliatus\Api\Exceptions\MissingRequestFieldException;
use Ciliatus\Api\Exceptions\UnhandleableRelationshipException;
use Ciliatus\Api\Traits\UsesDefaultDestroyMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultIndexMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultShowMethodTrait;
use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Core\Http\Requests\Request;
use Ciliatus\Core\Http\Requests\StoreHabitatRequest;
use Ciliatus\Core\Http\Requests\UpdateHabitatRequest;
use Ciliatus\Core\Models\Habitat;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use ReflectionException;

class HabitatController extends Controller
{

    use UsesDefaultIndexMethodTrait,
        UsesDefaultShowMethodTrait,
        UsesDefaultDestroyMethodTrait;

    /**
     * @param StoreHabitatRequest $request
     * @return JsonResponse
     * @throws MissingRequestFieldException
     * @throws UnhandleableRelationshipException
     * @throws ReflectionException
     * @throws AuthorizationException
     */
    public function store(StoreHabitatRequest $request): JsonResponse
    {
        /**
         * Pulls appliances from request as they are not directly related,
         * but need to be attached to the newly created built-in appliance group.
         *
         * @param StoreHabitatRequest $request
         * @return array|Collection
         */
        $pre = function (StoreHabitatRequest $request) {
            $appliances = new Collection();
            $appliance_ids = collect($request->valid()->get('relations'))->get('appliances') ?? [];
            foreach ($appliance_ids as $id) {
                $appliances[] = Appliance::find($id);
            }

            return $appliances;
        };

        /**
         * Attach appliances to the built-in appliance group.
         *
         * @param Collection $appliances
         * @param Habitat $habitat
         * @param Request $request
         */
        $post = function (Collection $appliances, Habitat $habitat, Request $request = null) {
            $group = $habitat->appliance_groups()->where('is_builtin', true)->first();
            foreach ($appliances as $appliance) {
                $group->appliances()->attach($appliance);
            }
        };

        return $this->_store($request, 'appliances', $pre, $post);
    }

    /**
     * @param UpdateHabitatRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws MissingRequestFieldException
     * @throws ReflectionException
     * @throws UnhandleableRelationshipException
     * @throws AuthorizationException
     */
    public function update(UpdateHabitatRequest $request, int $id): JsonResponse
    {
        /**
         * Pulls appliances from request as they are not directly related,
         * but need to be attached to the newly created built-in appliance group.
         *
         * @param UpdateHabitatRequest $request
         * @return array|Collection
         */
        $pre = function (UpdateHabitatRequest $request) {
            $appliances = new Collection();
            $appliance_ids = collect($request->valid()->get('relations'))->get('appliances') ?? [];
            foreach ($appliance_ids as $id) {
                $appliances[] = Appliance::find($id);
            }

            return $appliances;
        };

        /**
         * Attach appliances to the built-in appliance group.
         *
         * @param Collection $appliances
         * @param Habitat $habitat
         * @param Request $request
         */
        $post = function (Collection $appliances, Habitat $habitat, Request $request = null) {
            $group = $habitat->builtinApplianceGroup();
            foreach ($appliances as $appliance) {
                $group->appliances()->attach($appliance);
            }
        };

        return $this->_update($request, $id,'appliances', $pre, $post);
    }

}
