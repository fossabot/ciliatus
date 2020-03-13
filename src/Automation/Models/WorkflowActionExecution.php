<?php

namespace Ciliatus\Automation\Models;

use Carbon\Carbon;
use Ciliatus\Automation\Enum\WorkflowExecutionStateEnum;
use Ciliatus\Automation\Events\WorkflowActionExecutionStateChangeEvent;
use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowActionExecution extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'workflow_execution_id', 'workflow_action_id',
        'is_ready_to_start', 'is_completed',
        'started_at', 'ended_at', 'status', 'status_text',
        'workflow_time_offset_seconds', 'target_level', 'target_level_rampup_seconds'
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
     * @return BelongsTo
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(WorkflowAction::class, 'workflow_action_id');
    }

    /**
     * @return BelongsTo
     */
    public function workflow_execution(): BelongsTo
    {
        return $this->belongsTo(WorkflowExecution::class, 'workflow_execution_id');
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

        event(new WorkflowActionExecutionStateChangeEvent($this, $status));

        return $this;
    }

    /**
     * @return WorkflowActionExecution
     */
    public function setCompleted(): self
    {
        $this->setStatus(WorkflowExecutionStateEnum::STATE_ENDED());
        $this->is_completed = true;
        $this->save();

        $this->workflow_execution->checkCompleted();

        return $this;
    }

    /**
     * @param string $text
     * @return WorkflowActionExecution
     */
    public function error(string $text): self
    {
        $this->setStatus(WorkflowExecutionStateEnum::STATE_ERROR(), $text);

        $this->workflow_execution->error($text);

        return $this;
    }

    /**
     * @return WorkflowActionExecution
     */
    public function ready(): self
    {
        return $this->setStatus(WorkflowExecutionStateEnum::STATE_WAITING());
    }

    /**
     * @param Controlunit $controlunit
     * @return $this
     */
    public function claim(Controlunit $controlunit): self
    {
        if ($this->status == WorkflowExecutionStateEnum::STATE_WAITING()) {
            $this->claimed_by_controlunit_id = $controlunit->id;
            $this->claimed_at = Carbon::now();
            $this->setStatus(WorkflowExecutionStateEnum::STATE_CLAIMED());
            $this->save();
        }

        return $this;
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
     * @param Controlunit $controlunit
     * @return bool
     */
    public function isClaimedBy(Controlunit $controlunit): bool
    {
        return $this->claimed_by_controlunit_id == $controlunit->id;
    }

}