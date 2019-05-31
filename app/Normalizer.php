<?php
namespace Faryar76;

class Normalizer
{
    public function username($username)
    {
        return preg_replace('/[^\p{L}\p{N}\s]/u', '', $username);
    }
}
