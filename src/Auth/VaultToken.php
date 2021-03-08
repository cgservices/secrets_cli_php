<?php

namespace SecretsCli\Auth;

use Jippi\Vault\Client;
use GuzzleHttp\Psr7\Request;
use Jippi\Vault\Services\Data;

class VaultToken extends Data
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
    }

    public function get($key)
    {
    	$request = new Request('GET', '/v1/'.$key, ['X-Vault-Token' => getenv('VAULT_AUTH_TOKEN')]);
        return $this->client->send(
        	$request,
            ['base_url' => getenv('VAULT_ADDR')]
        );
    }
}