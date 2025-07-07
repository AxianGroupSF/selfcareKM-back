<?php
namespace App\Exception;

class GeneralException extends \RuntimeException
{
    public function __construct(
        string $message,
        private int $statusCode = 500
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
