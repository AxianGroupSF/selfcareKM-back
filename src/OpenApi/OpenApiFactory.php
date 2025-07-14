<?php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $pathItem = $openApi->getPaths()->getPath('/api/users/{id}/companies');
        if ($pathItem) {
            $operation = $pathItem->getPatch();
            if ($operation instanceof Operation) {
                $newOperation = $operation->withSummary('Updates companies linked to a user (IT Manager)')
                    ->withDescription('This operation replaces all user companies.');

                $openApi->getPaths()->addPath('/api/users/{id}/companies', $pathItem->withPatch($newOperation));
            }
        }

        $pathItem = $openApi->getPaths()->getPath('/api/msisdnfleet/{id}/bundles');
        if ($pathItem) {
            $operation = $pathItem->getPatch();
            if ($operation instanceof Operation) {
                $newOperation = $operation->withSummary('Updates bundles linked to a msisdnfleet')
                    ->withDescription('This operation replaces all msisdnfleet bundles.');

                $openApi->getPaths()->addPath('/api/msisdnfleet/{id}/bundles', $pathItem->withPatch($newOperation));
            }
        }

        return $openApi;
    }
}
