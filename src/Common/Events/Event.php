<?php

namespace Ciliatus\Common\Events;

use Ciliatus\Common\Models\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

abstract class Event implements EventInterface, ShouldBroadcast
{

    use SerializesModels;

    /**
     * @var Model
     */
    public Model $model;

    /**
     * @var string
     */
    public string $broadcastQueue = 'default-queue';

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('default');
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return $this->model->enrich()->transform();
    }


}