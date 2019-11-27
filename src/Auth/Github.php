<?php

namespace SecretsCli\Auth;

use GuzzleHttp\Psr7\Request;
use Jippi\Vault\Client;

class Github
{
    /**
     * Client instance
     *
     * @var Client
     */
    private $client;

    private $token;

    /**
     * Create a new Sys service with an optional Client
     *
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();

        $this->token = getenv('VAULT_AUTH_TOKEN');
    }

    public function login()
    {
        $request = new Request(
            'POST',
            '/v1/auth/github/login',
            [],
            json_encode(
                [
                    'token' => $this->token
                ]
            )
        );

        $response = $this->client->send($request);

        $body = json_decode($response->getBody());

        return $body->auth->client_token;
    }

    public function get($key)
    {
        $clientToken = $this->login();

        return $this->client->get(
            '/v1/' . $key,
            [
                'base_url' => getenv('VAULT_ADDR'),
                'headers' => [
                    'X-Vault-Token' => $clientToken
                ]
            ]
        );
    }
}