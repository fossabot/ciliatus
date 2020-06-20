<?php

namespace Ciliatus\Monitoring\Http\Controllers;

use Carbon\Carbon;
use Ciliatus\Api\Traits\HasNoModelTrait;
use Ciliatus\Common\Enum\HttpStatusCodeEnum;
use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Ciliatus\Monitoring\Http\Requests\BatchIngestRequest;
use Ciliatus\Monitoring\Http\Requests\IngestRequest;
use Ciliatus\Monitoring\Models\LogicalSensor;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class IngestController extends Controller
{

    use HasNoModelTrait;

    /**
     * @param IngestRequest $request
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function ingest__store(IngestRequest $request): JsonResponse
    {
        $this->auth();

        $logical_sensor = Factory::findOrFail(LogicalSensor::class, $request->valid()->get('logical_sensor_id'));

        $reading = $logical_sensor->addReading(
            $request->valid()->get('raw_value'),
            Carbon::parse($request->valid()->get('read_at'))
        );

        return $this->respondWithModel($reading);
    }

    /**
     * @param BatchIngestRequest $request
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function batch_ingest__store(BatchIngestRequest $request): JsonResponse
    {
        $this->auth();

        $logical_sensor = Factory::findOrFail(LogicalSensor::class,  $request->valid()->get('logical_sensor_id'));

        $logical_sensor->enterBatchMode();

        $done_ok = 0;
        $done_err = 0;
        $errors = [];
        foreach ($request->valid()->get('payload') as $reading) {
            $read_at = $reading->read_at ? Carbon::parse($reading->read_at) : Carbon::now();
            try {
                $logical_sensor->addReading($reading->raw_value, $read_at);
                $done_ok++;
            } catch (Exception $ex) {
                $errors[] = $ex->getMessage();
                $done_err++;
            }
        }

        $logical_sensor->endBatchMode();

        if ($done_err > 0) {
            return $this->respondWithErrors($errors, HttpStatusCodeEnum::Unprocessable_Entity());
        }

        return $this->respondWithData([
            'count' => [
                'ok' => $done_ok,
                'error' => $done_err
            ]
        ]);
    }

}
