<?php

namespace Ciliatus\Api\Http\Requests;

interface RequestInterface
{

    /**
     * @return bool
     */
    public function authorize(): bool;

    /**
     * @return array
     */
    public function rules(): array;

    /**
     * @return array
     */
    public function messages(): array;

}