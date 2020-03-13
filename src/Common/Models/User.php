<?php

namespace Ciliatus\Common\Models;

use Ciliatus\Api\Http\Transformers\GenericTransformer;
use Illuminate\Notifications\Notifiable;

class User extends \App\User
{
    use Notifiable;

    public function transform(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}