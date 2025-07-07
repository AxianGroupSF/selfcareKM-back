<?php
namespace App\Exception\Normalizer;

use App\Exception\Contract\ApiExceptionNormalizerInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class HttpExceptionNormalizer implements ApiExceptionNormalizerInterface
{
    public function supports(Throwable $exception): bool
    {
        return $exception instanceof HttpExceptionInterface;
    }

    public function normalize(Throwable $exception): array
    {
        /** @var HttpExceptionInterface $exception */
        return [
            'status' => $exception->getStatusCode(),
            'detail' => $exception->getMessage() ?: 'HTTP Error',
        ];
    }
}
