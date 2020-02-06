<?php

namespace app\index\controller;


class Wechat extends Base
{
    private $checkFlag = false;

    public function _initialize()
    {
        parent::_initialize();
        $this->checkFlag = $this->checkSignature();
        // trace(['$getCheck' => $this->checkFlag]);
    }

    /**
     * 微信服务器验证
     * @return mixed|string|null
     */
    public function valid()
    {
        $checkFlag = $this->checkFlag;
        $echostr = getValue(self::$get, 'echostr');
        trace(['$checkFlag' => $checkFlag, '$echostr' => $echostr]);

        if ($this->checkFlag) return $echostr;

        return '';
    }

    /**
     * 微信公众号菜单/按钮 操作
     * @return mixed|string|null
     */
    public function updateMenu()
    {

        return '';
    }


    public function index()
    {
        // return "this is Index page";
        return "error";
    }

    private function checkSignature()
    {
        $signature = getValue(self::$get, 'signature');
        $timestamp = getValue(self::$get, 'timestamp');
        $nonce = getValue(self::$get, 'nonce');

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        // trace(['$tmpStr' => $tmpStr, '$signature' => $signature]);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}