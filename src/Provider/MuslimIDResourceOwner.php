<?php

namespace MuslimID\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class MuslimIDResourceOwner implements ResourceOwnerInterface
{
    public function __construct(protected array $response) {}

    public function getId(): string
    {
        return $this->response['id'];
    }

    public function getName(): string
    {
        return $this->response['name'] ?? '';
    }

    public function getEmail(): string
    {
        return $this->response['email'] ?? '';
    }

    public function toArray(): array
    {
        return $this->response;
    }
}