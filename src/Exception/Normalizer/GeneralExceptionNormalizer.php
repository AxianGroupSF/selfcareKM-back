<?php
namespace App\Exception\Normalizer;

use App\Exception\Contract\ApiExceptionNormalizerInterface;
use App\Exception\GeneralException;
use Throwable;

class GeneralExceptionNormalizer implements ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool
    {
        return $exception instanceof GeneralException;
    }

    public function normalize(Throwable $exception): array
    {
        /** @var GeneralException $exception */
        return [
            'status' => $exception->getCode(),
            'detail' => $exception->getMessage(),
        ];
    }
}
