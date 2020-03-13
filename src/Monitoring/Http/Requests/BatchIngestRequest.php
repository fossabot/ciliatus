<?php

namespace App\Http\Requests;

use Ciliatus\Api\Http\Requests\Request;

class BatchIngestRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'logical_sensor_id' => 'numeric|required',
            'payload'           => 'json|required'
        ];
    }
}
