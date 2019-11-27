<?php

namespace SecretsCli;

use SecretsCli\Vault\ServiceFactory;

class Vault {

    protected $factory = false;
    protected $vault = false;
    protected $method = null;
    protected $methodKeys = [];

    public function __construct()
    {
        $this->method = getenv('VAULT_AUTH_METHOD');
    }

    private function factory() {
        if(!$this->factory) {
            $options = [
                'base_uri' => getenv('VAULT_ADDR'),
                'headers' => [
                  'X-Vault-Token' => getenv('VAULT_AUTH_TOKEN')
                ]
            ];
            $this->factory = new ServiceFactory($options);
        }
      return $this->factory;
    }

    private function sys() {
      return $this->factory()->get('sys');
    }

    private function data() {
      return $this->factory()->get('data');
    }

    public function github() {
      return $this->factory()->get('github');
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
        $response = $this->getAuthClient()->get($key);
        return $this->extractSecrets($response);
    }

    private function extractSecrets($response)
    {
        $data = json_decode($response->getBody())->data;

        if (property_exists($data, 'secrets')) {
            return $data->secrets;
        }

        return $data->value;
    }

    public function getAuthClient()
    {
        if ($this->method != null) {
            return $this->factory()->get($this->method);
        }

        return $this->data();
    }

    public function write($key, $value) {
        $arrayKey = 'value';

        if ($this->method == 'github') {
            $arrayKey = 'secret';
        }

        $this->data()->write($key, [$arrayKey => $value]);
    }
}
