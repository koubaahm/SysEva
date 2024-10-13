<?php
namespace App\Service;

interface JWTServiceInterface
{
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string;
    public function isValid(string $token): bool;
    public function getPayload(string $token): array;
    public function getHeader(string $token): array;
    public function isExpired(string $token): bool;
    public function check(string $token, string $secret);
}
