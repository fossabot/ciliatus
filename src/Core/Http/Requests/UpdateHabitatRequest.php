<?php

namespace Ciliatus\Core\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateHabitatRequest extends Request
{

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('ciliatus_core__habitats', 'name')->ignore($this->habitat)
            ],
            'relations.habitat_type' => 'required',
            'relations.location' => 'required',
            'relations.*' => ''
        ];
    }

}