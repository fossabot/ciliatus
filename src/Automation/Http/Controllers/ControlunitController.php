<?php

namespace Ciliatus\Automation\Http\Controllers;

use Carbon\Carbon;
use Ciliatus\Automation\Enum\WorkflowExecutionStateEnum;
use Ciliatus\Automation\Http\Requests\ControlunitCheckinRequest;
use Ciliatus\Automation\Http\Requests\ControlunitLogRequest;
use Ciliatus\Automation\Http\Requests\ControlunitReportActionStateRequest;
use Ciliatus\Automation\Http\Requests\ControlunitReportApplianceStateRequest;
use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Automation\Models\ApplianceTypeState;
use Ciliatus\Automation\Models\Controlunit;
use Ciliatus\Automation\Models\WorkflowActionExecution;
use Ciliatus\Common\Enum\HttpStatusCodeEnum;
use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ControlunitController extends Controller
{

    /**
     * Update client version and retrieve time offset between client and Ciliatus
     *
     * @param ControlunitCheckinRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function checkin(ControlunitCheckinRequest $request, int $id): JsonResponse
    {
        $controlunit = Factory::findOrFail(Controlunit::class, $id);
        $client_time = Carbon::parse($request->valid()->get('datetime'));

        $controlunit->updateClientInfo($client_time, $request->valid()->get('version'));

        $result = $this->respondWithModel($controlunit);

        $controlunit->last_checkin_at = Carbon::now();
        $controlunit->save();

        return $result;
    }

    /**
     * Claim possible workflow action executions
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function claim(int $id): JsonResponse
    {
        $controlunit = Factory::findOrFail(Controlunit::class, $id);

        $claimed = new Collection();
        WorkflowActionExecution::where('state', WorkflowExecutionStateEnum::STATE_WAITING())
            ->get()->each(function(WorkflowActionExecution $execution) use ($id, $controlunit, &$claimed) {
                if ($execution->action->appliance->controlunit_id == $id) {
                    $claimed->add($execution->claim($controlunit));
                }
            });

        $result = $this->respondWithModels($claimed);

        $controlunit->last_claim_at = Carbon::now();
        $controlunit->save();

        return $result;
    }

    /**
     * Retrieve Controlunit queued start times
     *
     * @param int $id
     * @return JsonResponse
     */
    public function start_times(int $id): JsonResponse
    {
        $controlunit = Controlunit::findOrFail($id);

        $result = $this->respondWithModels($controlunit->ready_claimed_executions);

        $controlunit->last_start_times_at = Carbon::now();
        $controlunit->save();

        return $result;
    }

    /**
     * Retrieve Controlunit configuration
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function config(int $id): JsonResponse
    {
        $controlunit = Factory::findOrFail(Controlunit::class, $id);

        $result = $this->respondWithData([]);

        $controlunit->last_config_at = Carbon::now();
        $controlunit->save();

        return $result;
    }

    /**
     * Send Controlunit logs to Ciliatus
     *
     * @param ControlunitLogRequest $request
     * @param int $id
     */
    public function log(ControlunitLogRequest $request, int $id)
    {
        $controlunit = Controlunit::findOrFail($id);
    }

    /**
     * @param ControlunitReportApplianceStateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function report_appliance_state(ControlunitReportApplianceStateRequest $request, int $id): JsonResponse
    {
        /** @var Controlunit $controlunit */
        $controlunit = Factory::findOrFail(Controlunit::class, $id);
        /** @var Appliance $appliance */
        $appliance = Factory::findOrFail(Appliance::class, $request->valid()->get('appliance_id'));

        if (!$appliance->isControlledBy($controlunit)) {
            return $this->respondWithError(
                sprintf('Controlunit %s has no control over Appliance %s', $id, $appliance->id),
                HttpStatusCodeEnum::Unprocessable_Entity()
            );
        }

        /** @var ApplianceTypeState $state */
        $state = Factory::findOrFail(ApplianceTypeState::class, $request->valid()->get('state_id'));
        $appliance->setState($state);

        return $this->respondWithModel($appliance);
    }

    /**
     * @param ControlunitReportActionStateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function report_action_state(ControlunitReportActionStateRequest $request, int $id): JsonResponse
    {
        /** @var Controlunit $controlunit */
        $controlunit = Factory::findOrFail(Controlunit::class, $id);
        /** @var WorkflowActionExecution $action */
        $action_execution = Factory::findOrFail(WorkflowActionExecution::class, $request->valid()->get('action_execution_id'));

        if (!$action_execution->isClaimedBy($controlunit)) {
            return $this->respondWithError(
                sprintf('Controlunit %s has no control over Action Execution %s', $id, $action_execution->id),
                HttpStatusCodeEnum::Unprocessable_Entity()
            );
        }

        $action_execution->setStatus(
            WorkflowExecutionStateEnum::search($request->valid()->get('status')),
            $request->valid()->get('status_text')
        );

        return $this->respondWithModel($action_execution);
    }

}
