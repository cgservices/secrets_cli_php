<?php

namespace Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use SecretsCli\Command\ReadCommand;

class ReadCommandTest extends TestCase
{
    public function testReadCommandClassExists(): void
    {
        $this->assertTrue(class_exists(ReadCommand::class));
    }

    public function testReadCommandExtendsCommand(): void
    {
        $this->assertTrue(is_subclass_of(ReadCommand::class, \SecretsCli\Command::class));
    }

    public function testBriefReturnsCorrectDescription(): void
    {
        $command = new ReadCommand();
        $this->assertEquals('Use to only read from vault server without writing to secrets file', $command->brief());
    }
}
