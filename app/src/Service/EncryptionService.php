<?php


namespace App\Service;


class EncryptionService
{

    public function __construct(private string $key,
                                private string $cipher,
                                private string $algorithm)
    {
    }

    public function encrypt(string $string, $expiration = 100): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $ciphertext_raw = openssl_encrypt(
            (time() + $expiration) . static::SPLITTER . $string,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv);
        $hmac = hash_hmac($this->algorithm, $ciphertext_raw, $this->key, $as_binary = true);
        return bin2hex($iv . $hmac . $ciphertext_raw);
    }


    public function decrypt(string $string): string
    {
        $iv = substr($c = hex2bin($string), 0, $iv_len = openssl_cipher_iv_length($this->key));
        $hmac = substr($c, $iv_len, $sha2len = 32);

        $original_plaintext = openssl_decrypt(
            $ciphertext_raw = substr($c, $iv_len + $sha2len),
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv);

        if (hash_equals($hmac, hash_hmac($this->algorithm, $ciphertext_raw, $this->key, $as_binary = true))) {
            return $original_plaintext;
        }

        throw new \ValueError('Wrong file name');
    }
}