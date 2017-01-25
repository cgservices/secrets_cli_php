<?php

  namespace SecretsCli\Command;

  class PoliciesCommand extends \CLIFramework\Command {

    public function brief() { return 'Check what policies your auth has'; }

    public function execute() {
      $this->getLogger()->info('executing add command.');
    }
  }
