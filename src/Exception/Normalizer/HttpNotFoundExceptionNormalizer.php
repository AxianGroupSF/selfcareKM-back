<?php
namespace App\Exception\Normalizer;

use App\Exception\Contract\ApiExceptionNormalizerInterface;
use App\Exception\HttpNotFoundException;
use Throwable;

class HttpNotFoundExceptionNormalizer implements ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool
    {
        return $exception instanceof HttpNotFoundException;
    }

    public function normalize(Throwable $exception): array
    {
        /** @var HttpNotFoundException $exception */
        return [
            'status' => 404,
            'message' => $exception->getMessage(),
        ];
    }
}
