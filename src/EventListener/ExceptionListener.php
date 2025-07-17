<?php
namespace App\EventListener;

use App\Exception\Contract\ApiExceptionNormalizerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener
{
    /**
     * @param iterable<ApiExceptionNormalizerInterface> $normalizers
     */
    public function __construct(
        private iterable $normalizers,
        private LoggerInterface $logger
    ) {}

    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        // Recherche du normalizer approprié
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($exception)) {
                $data = $normalizer->normalize($exception);
                break;
            }
        }

        // Complète la réponse
        $data ??= [
            'status' => $exception->getCode(),
            'detail' => 'Unhandled exception',
        ];

        $responseData = [
            'type'    => 'https://tools.ietf.org/html/rfc2616#section-10',
            'message' => $exception->getMessage(),
            'title'   => 'An error occurred',
            ...$data,
        ];

        $event->setResponse(new JsonResponse($responseData, $data['status']));
    }
}
