<?php

  namespace SecretsCli\Command;

  class ReadCommand extends \CLIFramework\Command {

    public function brief() { return 'Use to only read from vault server without writing to secrets file'; }

    public function options($opts) {
      $opts->add('e|environment', 'Set environment, default: development');
      $opts->add('k|secrets_storage_key', 'Override secrets_storage_key');
    }

    public function execute() {
      $vault = new \SecretsCli\Vault();
      $secrets = $vault->get('secret/'. \SecretsCli\Application::$secrets_key);
      if(!empty($secrets)) {
        echo $secrets . PHP_EOL;
      } else {
        echo 'Received empty response'. PHP_EOL;
      }
    }
  }
