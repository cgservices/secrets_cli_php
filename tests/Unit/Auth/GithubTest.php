<?php

namespace Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use SecretsCli\Auth\Github;
use Jippi\Vault\Client;

class GithubTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        putenv('VAULT_AUTH_TOKEN=test-github-token');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        putenv('VAULT_AUTH_TOKEN');
    }

    public function testGithubClassInstantiation(): void
    {
        $github = new Github();
        $this->assertInstanceOf(Github::class, $github);
    }

    public function testGithubClassInstantiationWithClient(): void
    {
        $client = $this->createMock(Client::class);
        $github = new Github($client);
        $this->assertInstanceOf(Github::class, $github);
    }
}
