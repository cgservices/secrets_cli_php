<?php

  namespace SecretsCli;

  class Application extends \CLIFramework\Application {
    const NAME = 'SecretsCli for PHP';
    const VERSION = '1.0.0';

    public static $secrets_key = 'secret/project/';
    public static $secrets_file = './.env';

    public function init() {
      parent::init();

      $this->command('init');
      // $this->command('policies');
      $this->command('pull');
      $this->command('push');
      $this->command('read');
      // $this->topic('basic');
    }

    public static function secrets_key() {
      $env = getenv('APPLICATION_ENV');
      $env = (empty($env)) ? 'development' : $env ;
      return self::$secrets_key . $env;
    }

    public function info($msg) {
      $this->logger->notice($this->formatter->format($msg, 'strong_white'));
    }

    public function success($msg) {
      $this->logger->notice($this->formatter->format($msg, 'strong_green'));
    }

    public function warning($msg) {
      $this->logger->notice($this->formatter->format($msg, 'strong_red'));
    }
  }
