<?php

namespace App\Service\Encryption;

class EncryptionService
{
    /**
     * @var StripePaymentService
     * @access private
     * @static
     */
    private static $_instance = null;
    protected $ciphering;
    protected $iv_length;
    protected $options;
    protected $encryption_iv;
    protected $encryption_key;
    protected $cartService;

    public function __construct()
    {
        $this->ciphering = "AES-128-CTR";
        $this->iv_length = openssl_cipher_iv_length($this->ciphering);
        $this->options = 0;
        $this->encryption_iv = '1234567891011121';
        $this->encryption_key = "TakeMeBackToTheMoon";
    }

    public function encrypt($string)
    {
        return openssl_encrypt(
            $string,
            $this->ciphering,
            $this->encryption_key,
            $this->options,
            $this->encryption_iv
        );
    }

    public function decrypt($encrypted_string)
    {
        return openssl_decrypt(
            $encrypted_string,
            $this->ciphering,
            $this->encryption_key,
            $this->options,
            $this->encryption_iv
        );
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new EncryptionService();
        }
        return self::$_instance;
    }
}
