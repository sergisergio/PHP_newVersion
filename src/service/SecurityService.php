<?php

namespace Service;

class SecurityService {

    /**
    * Function str_random
    *
    * @param int $length length
    *
    * @return int
    */
    public function str_random($length)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }
}
