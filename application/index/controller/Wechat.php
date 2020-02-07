<?php

namespace app\index\controller;

use app\index\model\UserInfo;
use EasyWeChat\Factory;
use think\console\command\make\Model;

class Wechat extends Base
{
    private $checkFlag = false;

    private $config;

    public function _initialize()
    {
        parent::_initialize();
        $this->config = \think\Config::parse(self::$configPath . 'WeChat.ini', 'ini')['wechat'];
        trace(['config' => $this->config]);

        $this->checkFlag = $this->checkSignature();
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

        $token = $this->config['token'];
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

    public function getOpenId()
    {
        $this->oauth();
    }


    public function oauth()
    {
        $request = \think\Request::instance();

        $this->config['oauth'] = [
            'scopes' => ['snsapi_userinfo'],
            'callback' => $request->domain() . '/index/Wechat/oauthCallback',
        ];

        $app = Factory::officialAccount($this->config);
        $oauth = $app->oauth;

        $oauth->redirect()->send();

    }

    public function oauthCallback()
    {
        $app = Factory::officialAccount($this->config);
        $oauth = $app->oauth;
        $user = $oauth->user();

        $modelUserInfo = model('UserInfo');
        $userInfo = $modelUserInfo->where('open_id', $user->getId())
            ->find();
        if ($userInfo == null) {
            $modelUserInfo->open_id = $user->getId();
            $modelUserInfo->wx_nickname = $user->getNickname();
            $modelUserInfo->user_avatar = $user->getAvatar();
            $modelUserInfo->wx_appid = $this->config['app_id'];
            $modelUserInfo->save();
        } else {
            $modelUserInfo->wx_nickname = $user->getNickname();
            $modelUserInfo->user_avatar = $user->getAvatar();
            $modelUserInfo->wx_appid = $this->config['app_id'];
            $modelUserInfo->save();
        }

        $data = [
            'wxAppid' => $this->config['app_id'],
            'openId' => $user->getId(),
            'wxNickname' => $user->getNickname(),
            'userAvatar' => $user->getAvatar(),
        ];

        session('wx', $data);

        $request = \think\Request::instance();

        header('location:' . $request->domain());
    }

    public function test()
    {
        $modelUserInfo = new UserInfo;
        $modelUserInfo->test();
    }
}