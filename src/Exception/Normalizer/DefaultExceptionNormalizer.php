<?php
namespace App\Exception\Normalizer;

use App\Exception\Contract\ApiExceptionNormalizerInterface;
use Throwable;

class DefaultExceptionNormalizer implements ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool
    {
        return true; // fallback
    }

    public function normalize(Throwable $exception): array
    {
        return [
            'status' => 500,
            'detail' => 'Internal Server Error',
        ];
    }
}
