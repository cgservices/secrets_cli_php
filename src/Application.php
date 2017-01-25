<?php

  namespace SecretsCli;

  class Application extends \CLIFramework\Application {
    const NAME = 'SecretsCli for PHP';
    const VERSION = '1.0.0';

    public function init() {
      parent::init();
      $this->command('init');
      $this->command('policies');
      $this->command('pull');
      $this->command('push');
      $this->command('read');
      // $this->topic('basic');
    }
  }
