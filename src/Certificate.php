<?php
/**
 * 认证
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Certificate{
    private $verifyRequestSign = false;

    const URL_SCHEME = 'https';
    const URL_HOST = 'duer.bdstatic.com';

    /**
     * @param string $privateKeyContent 私钥内容，使用监控统计功能或者推送功能需要提供
     * @return null
     */
    public function __construct($privateKeyContent = '') {
        //TODO data: requestbody + timespan
        //$this->data = json_encode($request->getData());
        $this->data = file_get_contents("php://input");
        $this->privateKey = $privateKeyContent;
        $this->verifyRequestSign = false;
    }

    /**
     * 开启验证请求参数签名，阻止非法请求
     *
     * @param null
     * @return null
     */
    public function enableVerifyRequestSign() {
        $this->verifyRequestSign = true; 
    }

    /**
     * 关闭验证请求参数签名
     *
     * @param null
     * @return null
     */
    public function disableVerifyRequestSign() {
        $this->verifyRequestSign = false; 
    }

    /**
     * @desc 判断是否是百度域
     * @param string $url
     * @return bool
     */
    public static function isBaiduDomain($url){
        $array = parse_url($url);
        $scheme = isset($array['scheme']) ? $array['scheme'] : '';
        $host = isset($array['host']) ? $array['host'] : '';

        if($scheme == self::URL_SCHEME && $host == self::URL_HOST){
            return true;
        }
        return false;
    }

    /**
     * @param null
     * @return resource
     */
    private function getRequestPublicKey() {
        //TODO get from head 
        //$filename = dirname(__file__).'/cacert.pem';
        $filename = $_SERVER['HTTP_SIGNATURECERTURL'];
        if(!$filename || !self::isBaiduDomain($filename)) {
            return; 
        }

        $cache = dirname(__file__).'/'.md5($filename);
        $content = '';
        if(!file_exists($cache)) {
            $content = file_get_contents($filename);
            if(!$content) {
                return; 
            }

            file_put_contents($cache, $content, LOCK_EX);
        }

        $content = $this->getFileContentSafety($cache); 

        return openssl_pkey_get_public($content);
    }

    /**
     * @desc 高并发情况下，避免由于证书更新导致不安全读写
     *
     * @param string $filename 文件名
     * @return string $content
     */
    private function getFileContentSafety($filename) {
        $file = fopen($filename, 'r');
        flock($file, LOCK_SH);
        $content = file_get_contents($filename);
        flock($file, LOCK_UN);
        fclose($file);

        return $content;
    }

    /**
     * @desc 验证请求者是否合法
     * @param null
     * @return boolean
     */
    public function verifyRequest() {
        if(!$this->verifyRequestSign) {
            return true; 
        }

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


