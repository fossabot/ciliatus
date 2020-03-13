<?php

namespace Ciliatus\Automation\Models;

use Carbon\Carbon;
use Ciliatus\Automation\Enum\WorkflowExecutionStateEnum;
use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Controlunit extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'health',
        'client_version', 'client_datetime', 'client_datetime_offset_seconds'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_checkin_at', 'last_claim_at', 'last_start_times_at', 'last_config_at'
    ];

    /**
     * @return HasMany
     */
    public function claimed_executions(): HasMany
    {
        return $this->hasMany(WorkflowActionExecution::class, 'claimed_by_controlunit_id');
    }

    /**
     * @return HasMany
     */
    public function ready_claimed_executions(): HasMany
    {
        return $this->claimed_executions()->where('status', WorkflowExecutionStateEnum::STATE_READY_TO_START());
    }

    /**
     * @param Carbon $client_time
     * @param string $version
     * @return $this
     */
    public function updateClientInfo(Carbon $client_time, string $version): self
    {
        $this->client_version = $version;
        $this->client_datetime = $client_time;
        $this->client_datetime_offset_seconds = Carbon::now()->diffInSeconds($client_time);
        $this->save();

        return $this;
    }

}