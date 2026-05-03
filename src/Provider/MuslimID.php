<?php

namespace MuslimID\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Override;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

class MuslimID extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'id';

    public function __construct(
        protected string $domain,
        array $options = [],
        array $collaborators = [],
    ) {
        parent::__construct($options, $collaborators);
    }

    #[Override]
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . '/oauth/authorize';
    }

    #[Override]
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->domain . '/oauth/token';
    }

    #[Override]
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->domain . '/api/user';
    }

    #[Override]
    protected function getDefaultScopes(): array
    {
        return ['profile', 'email'];
    }

    #[Override]
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (isset($data['error'])) {
            throw new UnexpectedValueException(
                $data['error_description'] ?? $data['error']
            );
        }
    }

    #[Override]
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new MuslimIDResourceOwner($response);
    }

    #[Override]
    protected function getScopeSeparator()
    {
        return ' ';
    }
}