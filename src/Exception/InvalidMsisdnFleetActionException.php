<?php
namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidMsisdnFleetActionException extends InvalidCompanyActionException
{
    public function __construct(string $action)
    {
        parent::__construct(sprintf("Action invalide : '%s'. Les actions valides sont 'enable' ou 'disable'.", $action));
    }
}
