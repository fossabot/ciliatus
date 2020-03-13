<?php

namespace Ciliatus\Automation\Models;

use Carbon\Carbon;
use Ciliatus\Automation\Enum\WorkflowExecutionStateEnum;
use Ciliatus\Automation\Enum\WorkflowHistoryEventsEnum;
use Ciliatus\Automation\Events\WorkflowActionExecutionStateChangeEvent;
use Ciliatus\Automation\Events\WorkflowExecutionRuntimeExceededEvent;
use Ciliatus\Automation\Events\WorkflowExecutionStateChangeEvent;
use Ciliatus\Common\Models\Alert;
use Ciliatus\Common\Models\Model;
use Ciliatus\Common\Traits\HasAlertsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowExecution extends Model
{

    use HasAlertsTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'workflow_id', 'is_ready_to_start', 'is_completed',
        'started_at', 'ended_at', 'status', 'status_text', 'expected_runtime_seconds',
        'runtime_exceedance_warn', 'runtime_exceedance_crit'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_ready_to_start' => 'boolean',
        'is_completed' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'started_at', 'ended_at', 'start_queued_at'
    ];

    /**
     * @var array
     */
    protected $with = [
        'action_executions'
    ];

    /**
     * @return BelongsTo
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * @return HasMany
     */
    public function action_executions(): HasMany
    {
        return $this->hasMany(WorkflowActionExecution::class, 'workflow_execution_id');
    }

    /**
     * @param WorkflowExecutionStateEnum $status
     * @param string $text
     * @return $this
     */
    public function setStatus(WorkflowExecutionStateEnum $status, string $text = ''): self
    {
        $this->status = $status->getValue();
        $this->status_text = $text;
        $this->save();

        event(new WorkflowExecutionStateChangeEvent($this, $status));

        return $this;
    }

    /**
     * @return WorkflowExecution
     */
    public function ready(): self
    {
        if (!$this->is_ready_to_start && !$this->is_completed)
            $this->is_ready_to_start = true;

        $this->action_executions->each(function(WorkflowActionExecution $execution) {
            $execution->ready();
        });

        $this->setStatus(WorkflowExecutionStateEnum::STATE_WAITING());
        $this->calculateExpectedRuntime();
        $this->save();

        $this->writeHistory(WorkflowHistoryEventsEnum::WORKFLOW_EXECUTION_READY());

        return $this;
    }

    /**
     * @return $this
     */
    public function checkReadyToStart(): self
    {
        $unclaimed = $this->action_executions->filter(fn(WorkflowActionExecution $e) => $e->status != WorkflowExecutionStateEnum::STATE_CLAIMED());

        if ($unclaimed->count() < 1) {
            $start_time = Carbon::now()->addSeconds(20);

            $this->action_executions->each(function(WorkflowActionExecution $execution) use ($start_time) {
                $execution->readyToStart($start_time);
            });

            $this->readyToStart($start_time);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function checkCompleted(): self
    {
        $incomplete = $this->action_executions->filter(fn(WorkflowActionExecution $e) => !$e->is_completed);
        if ($incomplete->count() > 0) return $this;

        return $this->complete();
    }

    /**
     * @param Carbon $start_time
     * @return $this
     */
    public function readyToStart(Carbon $start_time): self
    {
        $this->start_queued_at = $start_time;
        $this->setStatus(WorkflowExecutionStateEnum::STATE_READY_TO_START());
        $this->save();

        return $this;
    }

    /**
     * @return WorkflowExecution
     */
    public function complete(): self
    {
        $this->action_executions->each(function(WorkflowActionExecution $action_execution) {
            $action_execution->setCompleted();
        });

        $last_action_ended = $this->action_executions()->orderBy('ended_at', 'desc')->first();
        $this->actual_runtime_seconds = $this->started_at->diffInSeconds($last_action_ended);
        $this->is_completed = true;
        $this->setStatus(WorkflowExecutionStateEnum::STATE_ENDED());

        $this->activeAlerts()->each(function (Alert $alert) {
            $alert->end();
        });

        $this->save();

        $this->writeHistory(WorkflowHistoryEventsEnum::WORKFLOW_EXECUTION_COMPLETED());

        return $this;
    }

    /**
     * @param string $text
     * @return WorkflowExecution
     */
    public function error(string $text): self
    {
        $this->action_executions->each(function(WorkflowActionExecution $action_execution) {
            $action_execution->setCompleted();
        });

        $last_action_ended = $this->action_executions()->orderBy('ended_at', 'desc')->first();
        $this->actual_runtime_seconds = $this->started_at->diffInSeconds($last_action_ended);
        $this->is_completed = true;
        $this->setStatus(WorkflowExecutionStateEnum::STATE_ERROR(), $text);
        $this->save();

        $this->writeHistory(WorkflowHistoryEventsEnum::WORKFLOW_EXECUTION_ERROR(), [$text]);

        return $this;
    }

    /**
     * @return WorkflowExecution
     */
    public function calculateExpectedRuntime(): self
    {
        $this->expected_runtime_seconds = $this->action_executions()
            ->orderBy('workflow_time_offset_seconds', 'desc')
            ->first()
            ->workflow_time_offset_seconds;

        $this->save();

        return $this;
    }

    /**
     *
     */
    public function checkHealth(): void
    {
        if (is_null($this->started_at)) return;

        $diff = $this->getExpectedRuntimeDiffSeconds();
        $tolerance = config('ciliatus_automation.workflow_expected_runtime_tolerance_seconds');
        $text = trans('ciliatus_automation::alerts.executions.runtime_exceeded');
        
        if ($diff + $tolerance > $this->runtime_exceedance_crit && !$this->has_runtime_exceedance_crit_alerted) {
            event(new WorkflowExecutionRuntimeExceededEvent($this, true, $text));
            $this->has_runtime_exceedance_crit_alerted = true;
            $this->save();
        } elseif ($diff + $tolerance > $this->runtime_exceedance_warn && !$this->has_runtime_exceedance_warn_alerted) {
            event(new WorkflowExecutionRuntimeExceededEvent($this, false, $text));
            $this->has_runtime_exceedance_warn_alerted = true;
            $this->save();
        }
    }

    /**
     * @return int
     */
    private function getExpectedRuntimeDiffSeconds(): int
    {
        if (is_null($this->started_at)) return 0;

        $end = $this->ended_at ?? Carbon::now();
        return $this->started_at->diffInSeconds($end, false) - $this->expected_runtime_seconds;
    }

}