<?php
namespace App\Exception\Normalizer;

use App\Exception\BadRequestException;
use App\Exception\Contract\ApiExceptionNormalizerInterface;
use Throwable;

class BadRequestExceptionNormalizer implements ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool
    {
        return $exception instanceof BadRequestException;
    }

    public function normalize(Throwable $exception): array
    {
        return [
            'status' => 400,
            'detail' => $exception->getMessage(),
        ];
    }
}
