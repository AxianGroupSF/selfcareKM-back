<?php
namespace App\Exception\Normalizer;

use App\Exception\AccessDeniedHttpException;
use App\Exception\Contract\ApiExceptionNormalizerInterface;
use Throwable;

class AccessDeniedHttpExceptionNormalizer implements ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool
    {
        return $exception instanceof AccessDeniedHttpException;
    }

    public function normalize(Throwable $exception): array
    {
        /** @var AccessDeniedHttpException $exception */
        return [
            'status'  => 403,
            'message' => $exception->getMessage(),
        ];
    }
}
