<?php

  namespace SecretsCli\Vault;

  class Sys extends \SecretsCli\Vault {

    public function __construct() {
      parent::__construct();
      $this->vault = $this->sf->get('sys');
    }
  }
