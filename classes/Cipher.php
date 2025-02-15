<?php
namespace oniclass;

class Cipher
{
    private $encryptionKey;
    private $cipher = 'AES-256-CBC';

    public function __construct($key)
    {
        $this->encryptionKey = hash('sha256', $key);
    }

    function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function base64UrlDecode($data)
    {
        $decoded = strtr($data, '-_', '+/');
        return base64_decode($decoded);
    }

    public function encryptURL($data, $expireInSeconds)
    {
        $iv             = openssl_random_pseudo_bytes(16);
        $expirationTime = time() + $expireInSeconds;
        $dataWithExpire = json_encode([ 'data' => $data, 'expire' => $expirationTime ]);
        $encrypted      = openssl_encrypt($dataWithExpire, $this->cipher, $this->encryptionKey, 0, $iv);
        $encryptedData  = $this->base64UrlEncode($iv . $encrypted);

        return $encryptedData;
    }

    public function decryptURL($encryptedString)
    {

        $decoded = $this->base64UrlDecode($encryptedString);
        if ($decoded === false) {
            die('Error: Decoding failed');
        }

        $iv            = substr($decoded, 0, 16);
        $encryptedData = substr($decoded, 16);

        $decrypted = openssl_decrypt($encryptedData, $this->cipher, $this->encryptionKey, 0, $iv);

        if ($decrypted === false) {
            return [ 'success' => false, 'error' => 'Decryption failed' ];
        }

        $dataArray = json_decode($decrypted, true);
        if (time() > $dataArray[ 'expire' ]) {
            return [ 'success' => false, 'error' => 'URL has expired' ];
        }

        return [ 'success' => true, 'data' => $dataArray[ 'data' ] ];
    }
}
