<?php

namespace App\Helpers;

use InvalidArgumentException;

class Nonce {
    //generate salt
    public function generateSalt($length = 10){
        //set up random characters
        $chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        //get the length of the random characters
        $char_len = strlen($chars)-1;
        //store output
        $output = '';
        //iterate over $chars
        while (strlen($output) < $length) {
            /* get random characters and append to output till the length of the output
             is greater than the length provided */
            $output .= $chars[ rand(0, $char_len) ];
        }
        //return the result
        return $output;
    }
    //store Nonce
    private function storeNonce($form_id, $nonce){
        //Argument must be a string
        if (is_string($form_id) == false) {
            throw new InvalidArgumentException("A valid Form ID is required");
        }
        //group Generated Nonces and store with md5 Hash
        $_SESSION['nonce'][$form_id] = md5($nonce);
        return true;
    }
    //hash tokens and return nonce
    public function generateNonce($form_id, $expiry_time, $length = 10){
        //our secret
        $secret = env('NONCE_SECRET');

        //secret must be valid. You can add your regExp here
        if (is_string($secret) == false || strlen($secret) < 10) {
            throw new InvalidArgumentException("A valid Nonce Secret is required");
        }
        //generate our salt
        $salt = self::generateSalt($length);
        //convert the time to seconds
        $time = time() + (60 * intval($expiry_time));
        //concatenate tokens to hash
        $toHash = $secret.$salt.$time;
        //send this to the user with the hashed tokens
        $nonce = $salt .':'.$form_id.':'.$time.':'.hash('sha256', $toHash);
        //store Nonce
        self::storeNonce($form_id, $nonce);
        //return nonce
        return $nonce;
    }
}
