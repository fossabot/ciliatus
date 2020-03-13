<?php

namespace Ciliatus\Common\Traits;

use Carbon\Carbon;
use Ciliatus\Common\Enum\AlertEventsEnum;
use Ciliatus\Common\Enum\AlertLevelsEnum;
use Ciliatus\Common\Models\History;
use Ciliatus\Common\Models\Alert;
use Ciliatus\Common\Models\Model;
use Ciliatus\Monitoring\Jobs\BroadcastAlertJob;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use phpDocumentor\Reflection\Types\Boolean;

trait HasAlertsTrait
{

    /**
     * @return MorphMany
     */
    public function alerts(): MorphMany
    {
        return $this->morphMany(Alert::class, 'belongsToModel');
    }

    /**
     * @return MorphMany
     */
    public function activeAlerts(): MorphMany
    {
        return $this->alerts()->where('is_ack', false)->whereNull('ended_at');
    }

    /**
     * @param AlertLevelsEnum $level
     * @param string $text
     * @param Carbon|null $started_at
     * @return Alert
     */
    public function alert(AlertLevelsEnum $level, string $text, Carbon $started_at = null): Alert
    {
        // If the alert already exists -> return
        if ($this->activeAlerts()->where('text', $text)->exists()) {
            return $this->updateAlert($level, $text);
        }

        $started_at = $started_at ?? Carbon::now();

        /** @var Alert $alert */
        $alert = $this->alerts()->create([
            'level' => $level,
            'text' => $text,
            'started_at' => $started_at->toDateTimeString()
        ]);

        if ($this->isAlertBroadcastingEnabled($alert)) {
            dispatch(new BroadcastAlertJob($alert));
        }

        return $alert;
    }

    /**
     * @param string $text
     * @param Carbon|null $started_at
     * @return Alert
     */
    public function alertWarning(string $text, Carbon $started_at = null): Alert
    {
        return $this->alert(AlertLevelsEnum::WARNING(), $text, $started_at);
    }

    /**
     * @param string $text
     * @param Carbon|null $started_at
     * @return Alert
     */
    public function alertCritical(string $text, Carbon $started_at = null): Alert
    {
        return $this->alert(AlertLevelsEnum::CRITICAL(), $text, $started_at);
    }

    /**
     * @param AlertLevelsEnum $level
     * @param string $text
     * @return Alert
     */
    public function updateAlert(AlertLevelsEnum $level, string $text): Alert
    {
        $alerts = $this->activeAlerts()->where('text', $text)->get();
        if ($alerts->count() > 1) {
            $alert = $alerts->first()->deduplicate();
        } else {
            $alert = $alerts->first();
        }

        if ($alert->level == AlertLevelsEnum::WARNING() && $level == AlertLevelsEnum::CRITICAL())
            $alert->escalate();

        return $alert;
    }

    /**
     * @param string $text
     * @return Alert|null
     */
    public function hasActiveAlert(string $text): ?Alert
    {
        /** @var Alert $alert */
        $alert = $this->activeAlerts()->where('text', $text)->first();
        return $alert;
    }

    /**
     * @param string $text
     */
    public function endActiveAlert(string $text): void
    {
        if ($alert = $this->hasActiveAlert($text)) {
            $alert->end();
        }
    }

    /**
     * @param Alert $alert
     * @return bool
     */
    public function isAlertBroadcastingEnabled(Alert $alert): bool
    {
        $property = 'is_alert_broadcasting_enabled_' . $alert->level;
        return $this->$property;
    }

}
