<?php

  namespace SecretsCli\Command;

  class ReadCommand extends \CLIFramework\Command {

    public function brief() { return 'Use to only read from vault server without writing to secrets file'; }

    public function options($opts) {
      $opts->add('e|environment', 'Set environment, default: development');
      $opts->add('k|secrets_storage_key', 'Override secrets_storage_key');
    }

    public function arguments($args) {
      # XXX: Add a DSL here to support zsh/bash function completion
      $args->add('file');
    }


    public function execute() {
      $this->getLogger()->info('executing add command.');
    }
  }
