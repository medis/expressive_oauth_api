<?php

namespace Auth\Entity;

use Ramsey\Uuid\Uuid;

class Entity
{

    public function __construct(array $params = [])
    {
        $this->id = (string)Uuid::uuid4();
        $this->data($params);
    }

    public function data(array $params = []) {
        foreach (array_filter($params) as $key => $value) {
            $key = $this->toCamelCase($key);
            $func = "set{$key}";
            if (method_exists($this, $func)) {
                $this->{$func}($value);
            }
        }
    }

    private function toCamelCase($string)
    {
        $str = str_replace('_', '', ucwords($string, '_'));
        return lcfirst($str);
    }

}