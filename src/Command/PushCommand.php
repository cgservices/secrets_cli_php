<?php

  namespace SecretsCli\Command;

  class PushCommand extends \CLIFramework\Command {

    public function brief() { return 'Use to write to vault server from secrets file'; }

    public function options($opts) {
      $opts->add('y|ci_mode', 'CI mode (disables prompts and outputs)'); // @TODO overwrite
      $opts->add('e|environment', 'Set environment, default: development'); // @TODO overwrite
      $opts->add('f|secrets_file', 'Override secrets_file'); // @TODO overwrite
      $opts->add('k|secrets_storage_key', 'Override secrets_storage_key'); // @TODO overwrite
    }

    // public function arguments($args) {
    //   # XXX: Add a DSL here to support zsh/bash function completion
    //   $args->add('file');
    // }


    public function execute() {
      // $data = new \SecretsCli\Vault\Data();
      // $secrets_object = $data->get('secret/cgpay/development');
      // $secrets = json_decode($secrets_object->getBody())->data->value;

      $vault = new \SecretsCli\Vault();
      $vault->unseal();
      $vault->write('secrets/cgpay/production', 'Where are my secrets??');
      $this->getLogger()->info('executing add command.');
      return true;
    }
  }
