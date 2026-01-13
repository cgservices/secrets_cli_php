<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SecretsCli\Application;

class ApplicationTest extends TestCase
{
    private Application $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = new Application();
    }

    public function testApplicationConstants(): void
    {
        $this->assertEquals('SecretsCli for PHP', Application::NAME);
        $this->assertEquals('1.0.0', Application::VERSION);
    }

    public function testSecretsKeyWithExplicitEnvironment(): void
    {
        $result = Application::secrets_key('production');
        $this->assertEquals('secret/project/production', $result);
    }

    public function testSecretsKeyWithDevelopmentEnvironment(): void
    {
        $result = Application::secrets_key('development');
        $this->assertEquals('secret/project/development', $result);
    }

    // Note: Testing empty environment is skipped because Application::secrets_key()
    // uses die() which cannot be caught in tests. Consider refactoring to throw an exception.

    public function testSecretsKeyWithEnvironmentVariable(): void
    {
        putenv('APPLICATION_ENV=staging');
        $result = Application::secrets_key();
        $this->assertEquals('secret/project/staging', $result);
        putenv('APPLICATION_ENV'); // Clean up
    }

    public function testSecretsKeyDefaultsToDevelopment(): void
    {
        putenv('APPLICATION_ENV'); // Clear environment variable
        $result = Application::secrets_key();
        $this->assertEquals('secret/project/development', $result);
    }
}
