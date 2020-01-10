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

    public function checkCsrf($token, $session_token) {
        if (isset($token) AND isset($session_token) AND !empty($session_token) AND !empty($token)) {
            if ($token == $session_token) {
                return true;
            }
        }
    }
}
