<?php

namespace Ciliatus\Monitoring\Jobs;

use Ciliatus\Common\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BroadcastAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Alert $alert
     */
    protected Alert $alert;

    /**
     * @param Alert $alert
     */
    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->alert->broadcast();
    }
}
