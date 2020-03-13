<?php

namespace Ciliatus\Automation\Models;

use Carbon\Carbon;
use Ciliatus\Automation\Enum\WorkflowHistoryEventsEnum;
use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

class Workflow extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'start_after_min_interval_asap',
        'runtime_exceedance_warn', 'runtime_exceedance_crit'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_running' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_run_started_at', 'last_run_ended_at'
    ];

    /**
     * @var array
     */
    protected $with = [
        'actions', 'capabilities', 'active_executions',
        'startMetricConditions', 'startTimeConditions'
    ];

    /**
     * @return MorphTo
     */
    public function belongsToModel(): MorphTo
    {
        return $this->morphTo('belongsToModel', 'belongsToModel_type', 'belongsToModel_id');
    }

    /**
     * @return BelongsToMany
     */
    public function capabilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Capability::class,
            'ciliatus_automation__workflow_capability_pivot',
            'capability_id',
            'workflow_id'
        );
    }

    /**
     * @return HasMany
     */
    public function actions(): HasMany
    {
        return $this->hasMany(WorkflowAction::class, 'workflow_id')->orderBy('workflow_time_offset_seconds');
    }

    /**
     * @return HasMany
     */
    public function executions(): HasMany
    {
        return $this->hasMany(WorkflowExecution::class, 'workflow_id');
    }

    /**
     * @return HasMany
     */
    public function active_executions(): HasMany
    {
        return $this->executions()->where('is_completed', false);
    }

    /**
     * @return HasMany
     */
    public function startMetricConditions(): HasMany
    {
        return $this->hasMany(WorkflowStartMetricCondition::class, 'workflow_id');
    }

    /**
     * @return HasMany
     */
    public function startTimeConditions(): HasMany
    {
        return $this->hasMany(WorkflowStartTimeCondition::class, 'workflow_id');
    }

    /**
     * @param Carbon|null $time
     * @return bool
     */
    public function isReadyToStart(Carbon $time = null): bool
    {
        $time = $time ?? Carbon::now();

        $appliances_ready = $this->actions
            ->map(fn(WorkflowAction $action) => $action->isReadyToStart())
            ->filter(fn(bool $ready) => !$ready)
            ->count() < 1;

        return !$this->isRunning() && $appliances_ready && $this->hasEnoughTimePassedSinceLastRun($time);
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        if ($this->is_running) return true;

        if ($this->executions()->where('is_completed', false)->exists()) {
            Log::warning(sprintf(
                'Workflow %s has incomplete executions even though is_running is false. Correcting',
                $this->id
            ));

            $this->is_running = true;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * @param Carbon|null $time
     * @return bool
     */
    public function hasEnoughTimePassedSinceLastRun(Carbon $time = null): bool
    {
        $time = $time ?? Carbon::now();

        if (is_null($this->last_run_started_at)) return true;

        return $this->last_run_started_at
            ->addMinutes($this->minimum_interval_between_executions_minutes)
            ->isBefore($time);
    }

    /**
     * @param Model $caller
     * @return bool
     */
    public function start(Model $caller = null): bool
    {
        if (!$this->isReadyToStart()) {
            if (!is_null($caller)) $params = [$caller->model(), $caller->id];
            else $params = ['manual'];

            $this->writeHistory(WorkflowHistoryEventsEnum::WORKFLOW_START_NOTREADY(), $params);

            return false;
        }

        $this->last_run_started_at = Carbon::now();
        $this->is_running = true;
        $this->save();

        $execution = $this->generateExecution();
        $execution->ready();

        return true;
    }

    /**
     * @return WorkflowExecution
     */
    private function generateExecution(): self
    {
        /** @var WorkflowExecution $execution */
        $execution = $this->executions()->create();

        $this->writeHistory(WorkflowHistoryEventsEnum::WORKFLOW_EXECUTION_CREATED());

        $this->actions->each(function (WorkflowAction $action) use ($execution) {
            $action->generateExecution($execution);
        });

        return $execution;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return '';
    }

}
