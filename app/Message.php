<?php
namespace Faryar76;
use ArrayAccess;
use arrayObject;
class Message implements ArrayAccess
{
    protected $data;
    public function __construct($data=[]) {
        $this->data=$data;
    }
    public function toArray()
    {
        return $this->data;
    }
    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }
    public function __set($key,$value)
    {
        $this->data[$key]=$value;
    }
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}