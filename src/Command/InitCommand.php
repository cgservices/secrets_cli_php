<?php

  namespace SecretsCli\Command;

  class InitCommand extends \SecretsCli\Command {

    public function brief() { return 'Use to initialize project, create .secrets file'; }

    public function options($opts) {
      // $opts->add('f|secrets_file', 'Define secrets file'); // @TODO overwrite
      // $opts->add('k|secrets_storage_key', 'Define secrets storage_key'); // @TODO overwrite
    }

    public function execute() {
      $key = \SecretsCli\Application::$secrets_key;
      $file = \SecretsCli\Application::$secrets_file;

      if(file_exists(getcwd() .'/.secrets')) {
        $prompter = new \CLIFramework\Prompter();
        $answer = $prompter->ask('Already initialized, do you wish to reinitialize to defaults?',array('yes','no'));
        if($answer == 'no'){
          exit;
        }
      }

      if(is_writable(getcwd())) {
        if(file_put_contents(getcwd() .'/.secrets', "# File where your secrets are kept\r\nSECRETS_STORAGE_KEY=$key\r\n\r\n# Vault 'storage_key' where your secrets will be kept\r\nSECRETS_FILE=$file\r\n") !== false) {
          $this->success('Created .secrets file successfully!');
        } else {
          $this->warning('Failed to write to file, check permissions');
        }
      } else {
        $this->warning('No write permissions in the current directory, check permissions');
      }
    }
  }
