<?php

namespace Ciliatus\Core\Http\Requests;

class StoreHabitatRequest extends Request
{

    public function rules(): array
    {
        return [
            'name' => 'required|unique:ciliatus_core__habitats',
            'relations.habitat_type' => 'required',
            'relations.*' => ''
        ];
    }

}