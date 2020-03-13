<?php

namespace Ciliatus\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ControllerInterface
{

    public function __construct(Request $request);

    public function index(): JsonResponse;

    public function show(int $id): JsonResponse;

}