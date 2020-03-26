<?php

namespace Ciliatus\Common\Http\Requests;

class AcknowledgeAlertRequest extends Request
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer'
        ];
    }

}