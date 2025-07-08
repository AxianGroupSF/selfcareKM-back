<?php
namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HttpNotFoundException extends NotFoundHttpException
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
