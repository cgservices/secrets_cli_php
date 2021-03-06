<?php

  namespace SecretsCli\Command;

  class PullCommand extends \SecretsCli\Command {

    public function brief() { return 'Use to read from vault server to secrets file'; }

    public function options($opts) {
      // $opts->add('y|ci_mode', 'CI mode (disables prompts and outputs)'); // @TODO overwrite

      $opts->add('e|environment:', 'Set environment, default: development')
           ->suggestions(array('development', 'staging' , 'production'));

      $opts->add('f|secrets-file:', 'Override secrets_file')
           ->suggestions(array('./.env', './application.ini'));

      // $opts->add('k|secrets_storage_key', 'Override secrets_storage_key'); // @TODO overwrite
      // $opts->add('d|secrets_dir', 'Override secrets_dir, default: "."'); // @TODO overwrite
    }

    public function execute() {
      $vault = new \SecretsCli\Vault();

      $secrets_key = ($this->options->environment !== null) ? \SecretsCli\Application::secrets_key($this->options->environment) : \SecretsCli\Application::secrets_key() ;
      $secrets = $vault->get($secrets_key);

      $this->compare($secrets);
      $this->write($secrets);
      return $secrets;
    }

    private function compare($secrets) {

      $secrets_file = $this->secrets_file();
      if(file_exists($secrets_file)) {
        similar_text($secrets, file_get_contents($secrets_file), $percent);
        if($percent < 100) {
          $this->getLogger()->writeln('There are some differences ('. round(100-$percent, 2) .'%) between '. $secrets_file .' (left side) and Vault (right side):'. PHP_EOL);

          $diff_tool = 'colordiff';
          if(strpos(`colordiff -v`, 'command not found')) {
            $diff_tool = 'diff';
          }

          $tmp_secrets_file = '/tmp/'. md5($secrets_file);
          if(file_put_contents($tmp_secrets_file, $secrets) !== false) {
            echo `$diff_tool -y $secrets_file $tmp_secrets_file`;
          }
          unlink($tmp_secrets_file);

          $prompter = new \CLIFramework\Prompter();
          $answer = $prompter->ask(PHP_EOL .'Are you sure you want to override '. $secrets_file .'?',array('yes','no'));
          if($answer == 'no'){
            exit;
          }
        }
      }
      return true;
    }

    private function write($secrets) {
      $this->getLogger()->writeln('Writing to '. \SecretsCli\Application::$secrets_file);

      $secrets_file = $this->secrets_file();
      if(!file_exists($secrets_file)){
        touch($secrets_file);
      }
      if(is_writeable($secrets_file)){
        if(file_put_contents($secrets_file, $secrets) !== false) {
          return true;
        }
      }
      $this->getLogger()->writeln('Unable to write to'. $this->secrets_file .' check permissions');
      return false;
    }

    private function secrets_file() {
      $secrets_file = \SecretsCli\Application::$secrets_file;
      if($this->options->{'secrets-file'} !== null) {
        $secrets_file = $this->options->{'secrets-file'};
      }
      return getcwd() .'/'. $secrets_file;
    }
  }
