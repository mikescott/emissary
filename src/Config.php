<?php
namespace mikescott\Emissary;

use ArrayAccess;

class Config implements ArrayAccess
{
    protected $config;

    public function __construct(Emissary $emissary)
    {
        $this->config = $emissary->getApp()->getContainer()->get('settings')->all();
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->config[] = $value;
        } else {
            $this->config[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }
}