<?php

  namespace SecretsCli;

  class Command extends \CLIFramework\Command {

    public function info($msg) {
      $this->logger->notice($this->formatter->format($msg, 'strong_white'));
    }

    public function success($msg) {
      $this->logger->notice($this->formatter->format($msg, 'strong_green'));
    }

    public function warning($msg) {
      $this->logger->notice($this->formatter->format($msg, 'strong_red'));
    }
  }
