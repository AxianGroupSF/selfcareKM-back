<?php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;

class CustomOpenApiCompanyFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $pathItem = new PathItem(
            ref: 'Toggle  company status',
            get: new Operation(
                operationId: 'changeCompanyStatusById',
                tags: ['Company'],
                responses: [
                    '200' => [
                        'description' => 'Statut société modifié',
                    ],
                ],
                summary: 'Active ou désactive une entreprise (enable/disable) via un appel POST.',
                parameters: [
                    [
                        'name'        => 'action',
                        'in'          => 'path',
                        'required'    => true,
                        'schema'      => ['type' => 'string'],
                        'description' => 'action : (enable/disable)',
                    ],
                    [
                        'name'     => 'id',
                        'in'       => 'path',
                        'required' => true,
                        'schema'   => ['type' => 'string'],
                    ],
                ],
            )
        );

        $openApi->getPaths()->addPath('/api/company/{action}/{id}', $pathItem);

        return $openApi;
    }
}
