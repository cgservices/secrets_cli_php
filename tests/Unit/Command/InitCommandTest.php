<?php

namespace Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use SecretsCli\Command\InitCommand;

class InitCommandTest extends TestCase
{
    public function testInitCommandClassExists(): void
    {
        $this->assertTrue(class_exists(InitCommand::class));
    }

    public function testInitCommandExtendsCommand(): void
    {
        $this->assertTrue(is_subclass_of(InitCommand::class, \SecretsCli\Command::class));
    }

    public function testBriefReturnsCorrectDescription(): void
    {
        $command = new InitCommand();
        $this->assertEquals('Use to initialize project, create .secrets file', $command->brief());
    }
}
