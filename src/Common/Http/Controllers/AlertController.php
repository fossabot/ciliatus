<?php

namespace Ciliatus\Common\Http\Controllers;


use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Ciliatus\Common\Http\Requests\AcknowledgeAlertRequest;
use Ciliatus\Common\Models\Alert;
use Illuminate\Http\JsonResponse;

class AlertController extends Controller
{

    /**
     * @param AcknowledgeAlertRequest $request
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function acknowledge__update(AcknowledgeAlertRequest $request): JsonResponse
    {
        $alert = Factory::findOrFail(Alert::class, $request->valid()->id);
        $alert->ack();

        return $this->respondWithModel($alert);
    }

}
