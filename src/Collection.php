<?php 
namespace Faryar76;
use arrayObject;
class Collection extends arrayObject
{
    
    public function toArray()
    {
        return $this->getArrayCopy();
    }
    public function first()
    {
        return current($this->getArrayCopy());
    }
    public function latest()
    {
        $data=$this->getArrayCopy();
        return end($data);
    }
}
