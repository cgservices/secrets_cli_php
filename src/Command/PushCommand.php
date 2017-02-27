<?php

  namespace SecretsCli\Command;

  class PushCommand extends \CLIFramework\Command {

    public function brief() { return 'Use to write to vault server from secrets file'; }

    public function options($opts) {
      // $opts->add('y|ci_mode', 'CI mode (disables prompts and outputs)'); // @TODO overwrite
      // $opts->add('e|environment', 'Set environment, default: development'); // @TODO overwrite
      // $opts->add('f|secrets_file', 'Override secrets_file'); // @TODO overwrite
      // $opts->add('k|secrets_storage_key', 'Override secrets_storage_key'); // @TODO overwrite
    }

    public function execute() {
      $secrets = file_get_contents(\SecretsCli\Application::$secrets_file);
      $vault = new \SecretsCli\Vault();
      $vault->write('secret/'. \SecretsCli\Application::$secrets_key, $secrets);
      return true;
    }
  }
