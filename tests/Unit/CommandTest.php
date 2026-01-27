<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SecretsCli\Command;

class CommandTest extends TestCase
{
    public function testCommandClassExists(): void
    {
        $this->assertTrue(class_exists(Command::class));
    }

    public function testCommandExtendsCliFrameworkCommand(): void
    {
        $this->assertTrue(is_subclass_of(Command::class, \CLIFramework\Command::class));
    }
}
