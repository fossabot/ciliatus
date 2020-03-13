<?php

namespace Ciliatus\Common\Models;

use Carbon\Carbon;
use Ciliatus\Common\Enum\AlertEventsEnum;
use Ciliatus\Common\Enum\AlertLevelsEnum;
use Ciliatus\Common\Events\AlertEndedEvent;
use Ciliatus\Common\Traits\HasHistoryTrait;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;
use ReflectionObject;

class Alert extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'level', 'text', 'started_at', 'ended_at', 'is_ack',
        'belongsToModel_type', 'belongsToModel_id'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_ack' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'started_at', 'ended_at'
    ];

    /**
     * @return MorphTo
     */
    public function belongsToModel(): MorphTo
    {
        return $this->morphTo('belongsToModel');
    }

    /**
     * @return Alert
     */
    public function ack(): self
    {
        $this->is_ack = true;
        $this->save();

        return $this;
    }

    /**
     * @param Carbon|null $ended_at
     * @return Alert
     */
    public function end(Carbon $ended_at = null): self
    {
        $ended_at = $ended_at ?? Carbon::now();

        $this->ended_at = $ended_at;
        $this->save();

        if (in_array(HasHistoryTrait::class, (new ReflectionObject($this))->getTraitNames())) {
            $this->writeHistory(AlertEventsEnum::ALERT_ENDED()(), [], $ended_at);
        }

        event(new AlertEndedEvent($this));

        return $this;
    }

    /**
     * @return Alert
     * @throws Exception
     */
    public function deduplicate(): self
    {
        $alerts = Alert::whereNull('ended_at')
            ->where('belongsToModel_type', $this->belongsToModel_type)
            ->where('belongsToModel_id', $this->belongsToModel_id)
            ->where('text', $this->text)
            ->get();

        if ($alerts->count() <= 1) return $this;

        Log::warning(sprintf('Alert %s deduplication found %s duplicates', $this->id,  $alerts->count()-1));

        $alerts->each(function(Alert $alert) {
            if ($alert->id == $this->id) return;

            if ($alert->level == AlertLevelsEnum::CRITICAL() && $this->level == AlertLevelsEnum::WARNING()) {
                $this->level = AlertLevelsEnum::CRITICAL();
                $this->save();
            }

            $alert->delete();
        });

        return $this;
    }

    /**
     * @return Alert
     */
    public function escalate(): self
    {
        if ($this->level == AlertLevelsEnum::WARNING()) {
            $this->level = AlertLevelsEnum::CRITICAL();
            $this->save();
            $this->writeHistory(AlertEventsEnum::ALERT_ESCALATED());
        }

        return $this;
    }

    /**
     * @return Alert
     */
    public function broadcast(): self 
    {
        //@TODO implement
        return $this;
    }

}