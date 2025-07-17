<?php
namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException as ExceptionAccessDeniedHttpException;

class AccessDeniedHttpException extends ExceptionAccessDeniedHttpException
{
    public function __construct(string $message = 'AccessDeniedHttpException')
    {
        parent::__construct($message);
    }
}
