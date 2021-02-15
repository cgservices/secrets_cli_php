<?php

namespace SecretsCli;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
        fwrite(STDOUT, sprintf('[%s] Message: %s Context: %s', $level, $message, json_encode($context)));
    }
}