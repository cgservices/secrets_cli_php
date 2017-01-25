<?php

  namespace SecretsCli\Vault;

  class Data extends \SecretsCli\Vault {

    public function __construct() {
      parent::__construct();
      $this->vault = $this->sf->get('data');
    }
  }
