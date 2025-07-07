<?php
namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestException extends BadRequestHttpException
{
    public function __construct(string $message = 'Invalid username or password')
    {
        parent::__construct($message);
    }
}
