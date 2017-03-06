<?php

  namespace SecretsCli\Command;

  class ReadCommand extends \SecretsCli\Command {

    public function brief() { return 'Use to only read from vault server without writing to secrets file'; }

    public function options($opts) {
      $opts->add('e|environment:', 'Set environment, default: development')
           ->suggestions(array('development', 'staging' , 'production'));

      // $opts->add('k|secrets_storage_key', 'Override secrets_storage_key');
    }

    public function execute() {
      $vault = new \SecretsCli\Vault();
      $secrets_key = ($this->options->environment !== null) ? \SecretsCli\Application::secrets_key($this->options->environment) : \SecretsCli\Application::secrets_key() ;
      $secrets = $vault->get($secrets_key);

      if(!empty($secrets)) {
        echo $secrets . PHP_EOL;
      } else {
        echo 'Received empty response'. PHP_EOL;
      }
    }
  }
