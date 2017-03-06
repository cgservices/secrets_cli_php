<?php

  namespace SecretsCli\Command;

  class PushCommand extends \SecretsCli\Command {

    public function brief() { return 'Use to write to vault server from secrets file'; }

    public function options($opts) {
      // $opts->add('y|ci_mode', 'CI mode (disables prompts and outputs)'); // @TODO overwrite

      $opts->add('e|environment:', 'Set environment, default: development')
           ->suggestions(array('development', 'staging' , 'production'));

      $opts->add('f|secrets-file:', 'Override secrets_file')
           ->suggestions(array('./.env', './application.ini'));

      // $opts->add('k|secrets_storage_key', 'Override secrets_storage_key'); // @TODO overwrite
    }

    public function execute() {
      $secrets_file = \SecretsCli\Application::$secrets_file;
      if($this->options->{'secrets-file'} !== null) {
        if(file_exists(getcwd() .'/'. $this->options->{'secrets-file'})) {
          $secrets_file = $this->options->{'secrets-file'};
        } else {
          $this->warning('Secrets file "'. $this->options->{'secrets-file'} .'" does not exist, can not continue...');
          die;
        }
      }

      $secrets_key = ($this->options->environment !== null) ? \SecretsCli\Application::secrets_key($this->options->environment) : \SecretsCli\Application::secrets_key() ;

      $secrets = file_get_contents(getcwd() .'/'. $secrets_file);
      $vault = new \SecretsCli\Vault();
      $vault->write($secrets_key, $secrets);
      $this->success('Push from "'. $this->options->{'secrets-file'} .'" to "'. $secrets_key .'" complete');
      return true;
    }
  }
