<?php

namespace Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use SecretsCli\Command\PushCommand;

class PushCommandTest extends TestCase
{
    public function testPushCommandClassExists(): void
    {
        $this->assertTrue(class_exists(PushCommand::class));
    }

    public function testPushCommandExtendsCommand(): void
    {
        $this->assertTrue(is_subclass_of(PushCommand::class, \SecretsCli\Command::class));
    }

    public function testBriefReturnsCorrectDescription(): void
    {
        $command = new PushCommand();
        $this->assertEquals('Use to write to vault server from secrets file', $command->brief());
    }
}
