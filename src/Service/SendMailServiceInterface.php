<?php
namespace App\Service;

interface SendMailServiceInterface
{
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void;
}
