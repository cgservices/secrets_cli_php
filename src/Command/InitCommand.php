<?php

  namespace SecretsCli\Command;

  class InitCommand extends \CLIFramework\Command {

    public function brief() { return 'Use to initialize project, create .secrets file'; }

    public function options($opts) {
      $opts->add('f|secrets_file', 'Define secrets file'); // @TODO overwrite
      $opts->add('k|secrets_storage_key', 'Define secrets storage_key'); // @TODO overwrite
    }

    public function execute() {
      // $this->getLogger()->info('executing add command.');
    }
  }
