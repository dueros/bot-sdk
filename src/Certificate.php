<?php
/**
 * 认证
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Certificate{
    /**
     * @param string $privateKeyContent 私钥内容，使用监控统计功能或者推送功能需要提供
     * @return null
     */
    public function __construct($privateKeyContent = '') {
        //TODO data: requestbody + timespan
        //$this->data = json_encode($request->getData());
        $this->data = file_get_contents("php://input");
        $this->privateKey = $privateKeyContent;
    }

    /**
     * @param null
     * @return resource
     */
    private function getRequestPublicKey() {
        //TODO get from head 
        //$filename = dirname(__file__).'/cacert.pem';
        $filename = $_SERVER['HTTP_SIGNATUREURL'];
        if(!$filename) {
            return; 
        }

        return openssl_pkey_get_public(file_get_contents($filename));
    }

    /**
     * @desc 验证请求者是否合法
     * @param null
     * @return boolean
     */
    public function verifyRequest() {
        $publicKey = $this->getRequestPublicKey(); 
        if(!$publicKey || !$this->data) {
            return false; 
        }

        $encryptedData = '';
        // 公钥解密
        $verify = openssl_verify($this->data, base64_decode($this->getRequestSig()), $publicKey, OPENSSL_ALGO_SHA1);

        return $verify == 1;
        //openssl_public_decrypt(base64_decode($this->getRequestSig()), $encryptedData, $publicKey);
        //$sig = sha1($this->data);
        //return $encryptedData == $sig;
    }

    /**
     * 生成签名，当使用DuerOS统计功能或者推送消息
     * @param string $content 待签名内容
     * @return string|boolean
     */
    public function getSig($content) {
        if(!$this->privateKey || !$content) {
            return false;
        }
        $privateKey = openssl_pkey_get_private($this->privateKey, '');
        $encryptedData = '';
        // 私钥加密
        openssl_sign($content, $encryptedData, $privateKey, OPENSSL_ALGO_SHA1);
        return base64_encode($encryptedData);
    }

    /**
     * @param null
     * @return string
     */
    private function getRequestSig() {
        //TODO: get from http request
        return  $_SERVER['HTTP_SIGNATURE'];

        //for test
        //return $this->getSig(file_get_contents(dirname(__file__).'/privkey.pem')); 
    }
}


