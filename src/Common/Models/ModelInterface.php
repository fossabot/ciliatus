<?php

namespace Ciliatus\Common\Models;

interface ModelInterface
{

    public function transform(): array;

    public function self(): string;

    public function fk(): string;

    public function model(): string;

    public function getIcon(): string;

}
