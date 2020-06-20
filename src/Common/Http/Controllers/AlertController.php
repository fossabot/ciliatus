<?php

namespace Ciliatus\Common\Http\Controllers;


use Ciliatus\Api\Traits\UsesDefaultCreateMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultDestroyMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultIndexMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultShowMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultUpdateMethodTrait;
use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Ciliatus\Common\Http\Requests\AcknowledgeAlertRequest;
use Ciliatus\Common\Models\Alert;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class AlertController extends Controller
{

    use UsesDefaultCreateMethodTrait,
        UsesDefaultIndexMethodTrait,
        UsesDefaultShowMethodTrait,
        UsesDefaultUpdateMethodTrait,
        UsesDefaultDestroyMethodTrait;

    /**
     * @param AcknowledgeAlertRequest $request
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function acknowledge__update(AcknowledgeAlertRequest $request): JsonResponse
    {
        $this->auth();

        $alert = Factory::findOrFail(Alert::class, $request->valid()->id);
        $alert->ack();

        return $this->respondWithModel($alert);
    }

}
