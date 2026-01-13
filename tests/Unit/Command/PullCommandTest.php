<?php

namespace Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use SecretsCli\Command\PullCommand;

class PullCommandTest extends TestCase
{
    public function testPullCommandClassExists(): void
    {
        $this->assertTrue(class_exists(PullCommand::class));
    }

    public function testPullCommandExtendsCommand(): void
    {
        $this->assertTrue(is_subclass_of(PullCommand::class, \SecretsCli\Command::class));
    }

    public function testBriefReturnsCorrectDescription(): void
    {
        $command = new PullCommand();
        $this->assertEquals('Use to read from vault server to secrets file', $command->brief());
    }
}
