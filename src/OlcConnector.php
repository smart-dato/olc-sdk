<?php

namespace SmartDato\Olc;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class OlcConnector extends Connector
{
    use AcceptsJson;

    public function __construct(
        readonly protected ?string $url = null,
        readonly protected ?string $token = null,
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->url ?? config('olc.base_url');
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator(
            token: $this->token ?? config('olc.token')
        );
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}
