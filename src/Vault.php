<?php

  namespace SecretsCli;

  class Vault {

    protected $factory = false;
    protected $vault = false;

    private function factory() {
      if(!$this->factory) {
        $options = [
          'base_uri' => getenv('VAULT_ADDR'),
          'headers' => [
            'X-Vault-Token' => getenv('VAULT_TOKEN')
          ]
        ];
        $this->factory = new \Jippi\Vault\ServiceFactory($options);
      }
      return $this->factory;
    }

    private function sys() {
      return $this->factory()->get('sys');
    }

    private function data() {
      return $this->factory()->get('data');
    }

    public function unsealed() {
      return $this->sys()->unsealed();
    }

    public function seal() {
      throw new \Exception("Not implemented yet", 1);
    }

    public function unseal() {
      if(!$this->unsealed()) {
        $this->getLogger()->writeln("#######################################\r\n#       Unable to unseal Vault!       #\r\n#######################################");
      }
      return true;
    }

    public function get($key) {
      $this->unseal();
      $response = $this->data()->get($key);
      return json_decode($response->getBody())->data->value;
    }

    public function write($key, $value) {
      $this->data()->write($key, ['value' => $value]);
    }
  }
