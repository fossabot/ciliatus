<?php

namespace Ciliatus\Monitoring\Http\Requests;

use Ciliatus\Api\Http\Requests\Request;

class IngestRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'logical_sensor_id' => 'numeric|required',
            'read_at' => 'date|required',
            'raw_value' => 'numeric|required'
        ];
    }
}
