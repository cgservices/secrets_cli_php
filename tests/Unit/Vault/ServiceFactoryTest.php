<?php

namespace Tests\Unit\Vault;

use PHPUnit\Framework\TestCase;
use SecretsCli\Vault\ServiceFactory;
use Jippi\Vault\Client;

class ServiceFactoryTest extends TestCase
{
    private ServiceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $options = [
            'base_uri' => 'http://localhost:8200',
            'headers' => [
                'X-Vault-Token' => 'test-token'
            ]
        ];
        $this->factory = new ServiceFactory($options);
    }

    public function testFactoryInstantiation(): void
    {
        $this->assertInstanceOf(ServiceFactory::class, $this->factory);
    }

    public function testGetSysService(): void
    {
        $service = $this->factory->get('sys');
        $this->assertInstanceOf(\Jippi\Vault\Services\Sys::class, $service);
    }

    public function testGetDataService(): void
    {
        $service = $this->factory->get('data');
        $this->assertInstanceOf(\Jippi\Vault\Services\Data::class, $service);
    }

    public function testGetGithubService(): void
    {
        $service = $this->factory->get('github');
        $this->assertInstanceOf(\SecretsCli\Auth\Github::class, $service);
    }

    public function testGetInvalidServiceThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The service "invalid" is not available');
        
        $this->factory->get('invalid');
    }
}
