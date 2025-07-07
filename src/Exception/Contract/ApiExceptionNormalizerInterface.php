<?php
namespace App\Exception\Contract;

use Throwable;

interface ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool;

    public function normalize(Throwable $exception): array;
}
