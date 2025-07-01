<?php
namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class InvalidCompanyActionException extends BadRequestHttpException
{
    public function __construct(string $action)
    {
        parent::__construct(sprintf("Action invalide : '%s'. Les actions valides sont 'enable' ou 'disable'.", $action));
    }
}
