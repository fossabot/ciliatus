<?php

namespace Ciliatus\Common\Traits;

use Carbon\Carbon;
use Ciliatus\Automation\Enum\WorkflowHistoryEventsEnum;
use Ciliatus\Common\Models\History;
use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasHistoryTrait
{

    /**
     * @return MorphMany
     */
    public function history(): MorphMany
    {
        return $this->morphMany(History::class, 'belongsToModel');
    }

    /**
     * @param string $event
     * @param array $params
     * @param Carbon $date
     */
    public function writeHistory(string $event, array $params = [], Carbon $date = null): void
    {
        $date = $date ?? Carbon::now();

        $this->history()->create([
            'event' => $event,
            'params' => implode('||', $this->parseParams($params)),
            'created_at' => $date->toDateTimeString()
        ]);
    }

    /**
     * @param array $params
     * @return array
     */
    protected function parseParams(array $params): array
    {
        return array_map(function ($param) {
            if (is_a($param, Model::class)) {
                return get_class($param) . '::' . $param->id;
            }

            return $param;
        }, $params);
    }

}
