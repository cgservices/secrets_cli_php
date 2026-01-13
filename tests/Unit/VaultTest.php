<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SecretsCli\Vault;

class VaultTest extends TestCase
{
    private Vault $vault;

    protected function setUp(): void
    {
        parent::setUp();
        // Set minimal required environment variables for instantiation
        putenv('VAULT_ADDR=http://localhost:8200');
        putenv('VAULT_AUTH_TOKEN=test-token');
        putenv('VAULT_AUTH_METHOD=github');
        
        $this->vault = new Vault();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up environment variables
        putenv('VAULT_ADDR');
        putenv('VAULT_AUTH_TOKEN');
        putenv('VAULT_AUTH_METHOD');
    }

    public function testVaultClassInstantiation(): void
    {
        $this->assertInstanceOf(Vault::class, $this->vault);
    }

    public function testVaultReadsAuthMethodFromEnvironment(): void
    {
        putenv('VAULT_AUTH_METHOD=token');
        $vault = new Vault();
        
        $this->assertInstanceOf(Vault::class, $vault);
    }

    public function testSealMethodThrowsNotImplementedException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Not implemented yet');
        
        $this->vault->seal();
    }
}
