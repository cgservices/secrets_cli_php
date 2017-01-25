<?php

  namespace SecretsCli;

  class Vault {

    protected $sf = false;
    protected $vault = false;

    public function __construct() {
      $options = array();
      $options['base_uri'] = getenv('VAULT_ADDR');
      $options['headers']['X-Vault-Token'] = getenv('VAULT_TOKEN');

      $this->sf = new \Jippi\Vault\ServiceFactory($options);
    }

    public function unsealed() {
      return $this->sf->get('sys')->unsealed();
    }

    public function seal() {

    }

    public function unseal() {
      if(!$this->unsealed()) {
        $this->getLogger()->writeln("#######################################\r\n#       Unable to unseal Vault!       #\r\n#######################################");
      }
      return true;
    }

    public function get($key) {

    }

    public function write($key, $value) {
      $this->sf->get('data')->write($key, array('value' => $value));
    }

    public function __call($name, $args) {
      if(is_callable(array($this->vault, $name))) {
        return call_user_func_array(array($this->vault, $name), $args);
      }
      throw new Exception("Undefined method ". $name .", are you not using the direct service objects?", 1);
    }
  }
