<?php

namespace Ciliatus\Api\Http\Controllers;

use Ciliatus\Api\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

interface ControllerInterface
{

    public function __construct(Request $request);

    public function index(): JsonResponse;

    public function show(int $id): JsonResponse;

}