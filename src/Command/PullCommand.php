<?php

  namespace SecretsCli\Command;

  class PullCommand extends \CLIFramework\Command {

    protected $secrets_file = '.env';
    protected $secrets_dir = '.';

    public function brief() { return 'Use to read from vault server to secrets file'; }

    public function options($opts) {
      $opts->add('y|ci_mode', 'CI mode (disables prompts and outputs)'); // @TODO overwrite
      $opts->add('e|environment', 'Set environment, default: development'); // @TODO overwrite
      $opts->add('f|secrets_file', 'Override secrets_file'); // @TODO overwrite
      $opts->add('k|secrets_storage_key', 'Override secrets_storage_key'); // @TODO overwrite
      $opts->add('d|secrets_dir', 'Override secrets_dir, default: "."'); // @TODO overwrite
    }

    public function execute() {
      $vault = new \SecretsCli\Vault();
      $vault->unseal();
      $secrets = $vault->get('secret/cgpay/development');
      $this->compare($secrets);
      $this->write($secrets);
      return $secrets;
    }

    private function compare($secrets) {
      $secrets_file = $this->secrets_dir .'/'. $this->secrets_file;
      if(file_exists($secrets_file)) {
        similar_text(file_get_contents($secrets_file), $secrets, $percent);
        $percent = 1;
        if($percent > 0) {
          $this->getLogger()->writeln('There are some differences between '. $this->secrets_file .' and vault:');

          // @TODO Show PrettyDiff output

          $prompter = new \CLIFramework\Prompter();
          $answer = $prompter->ask('Are you sure you want to override '. $this->secrets_file .'?',array('yes','no'));
          if($answer == 'no'){
            exit;
          }
        }
      }
      return true;
    }

    private function write($secrets) {
      $this->getLogger()->writeln('Writing to '. $this->secrets_file);

      $secrets_file = $this->secrets_dir .'/'. $this->secrets_file;
      if(!file_exists($secrets_file)){
        touch($secrets_file);
      }
      if(is_writeable($secrets_file)){
        $h = fopen($secrets_file, 'w');
        fwrite($h, $secrets);
        fclose($h);
        return true;
      }
      $this->getLogger()->writeln('Unable to write to'. $this->secrets_file .' check permissions');
      return false;
    }
  }
