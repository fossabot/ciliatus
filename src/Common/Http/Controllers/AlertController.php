<?php

namespace Ciliatus\Common\Http\Controllers;


use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Ciliatus\Common\Models\Alert;
use Illuminate\Http\JsonResponse;

class AlertController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function active(): JsonResponse
    {
        $query = Alert::with('belongsToModel')->whereNull('ended_at');
        return $this->respondFilteredAndPaginated($query);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function acknowledge(int $id): JsonResponse
    {
        $alert = Factory::findOrFail(Alert::class, $id);
        $alert->ack();

        return $this->respondWIthModel($alert);
    }

}
