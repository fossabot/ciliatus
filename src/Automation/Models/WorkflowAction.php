<?php

namespace Ciliatus\Automation\Models;

use Ciliatus\Automation\Enum\WorkflowHistoryEventsEnum;
use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowAction extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'workflow_id', 'appliance_type_state_id', 'appliance_id',
        'workflow_time_offset_seconds', 'target_level', 'target_level_rampup_seconds'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $with = [
        'state', 'appliance'
    ];

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(ApplianceTypeState::class, 'appliance_type_State_id');
    }

    /**
     * @return BelongsTo
     */
    public function appliance(): BelongsTo
    {
        return $this->belongsTo(Appliance::class, 'appliance_id');
    }

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
    public function executions(): HasMany
    {
        return $this->hasMany(WorkflowActionExecution::class, 'workflow_action_id');
    }

    /**
     * @param WorkflowExecution $workflowExecution
     * @return WorkflowActionExecution
     */
    public function generateExecution(WorkflowExecution $workflowExecution): self
    {
        /** @var WorkflowActionExecution $execution */
        $execution = $this->executions()->create([
            'workflow_time_offset_seconds' => $this->workflow_time_offset_seconds,
            'target_level' => $this->target_level,
            'target_level_rampup_seconds' => $this->target_level_rampup_seconds,
            'workflow_execution_id' => $workflowExecution->id
        ]);

        $this->writeHistory(WorkflowHistoryEventsEnum::WORKFLOW_ACTION_EXECUTION_CREATED());

        return $execution;
    }

    /**
     * @return bool
     */
    public function isReadyToStart(): bool
    {
        return !$this->executions()->count() > 0 && $this->appliance->isOk();
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return '';
    }

}
