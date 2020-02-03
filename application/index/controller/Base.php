<?php

namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Config;

// 引入类库

class Base extends Controller
{

    public static $post = null, $get = null, $route = null, $auth = null;
    public static $session = null;
    public static $c = null;
    public static $cm = null;
    public static $m = null;
    private static $r = null;

    public function _initialize()
    {
        // echo "Base -- index";
        self::$m = config("environment.value");
        switch (self::$m) {
            case "dev":
                self::$c = config("configDEV");
                break;
            case "pro":
                self::$c = config("configPRO");
                break;
        }
        self::$cm = config("common");


        $moduleName = request()->module();
        Session::prefix($moduleName);
        self::$post = request()->post();
        self::$get = request()->get();
        self::$route = request()->route();
        self::$session = session($moduleName);

        $this->assign('static_v', config('staticVer.version'));
//
        $cdn = self::$c['CDN'];
        $this->assign('cdn', $cdn[0]);
    }

    public function getRedis()
    {
        if (self::$r == null) {
            self::$r = new \Redis;

            $redisConfig = self::$c['redis'];

            self::$r->connect($redisConfig['host'], $redisConfig['port']);
            self::$r->select($redisConfig['selectDb']);
        }

        return self::$r;
    }

    public function getRedisKey($keyName = '')
    {
        if (!isset(self::$cm['redisKey'][$keyName]))
            return false;

        $redisKeyName = APP_NAME . ":" . self::$cm['redisKey'][$keyName];

        return $redisKeyName;
    }

    protected function getPushMsgConfig()
    {
        $c = Config::parse(APP_PATH . "extra/" . config("config.environment") . "/pushMsgImg.json", 'json', 'pushMsgImg');

        return $c;
    }

    protected function getUserInfo_Base($openId = '')
    {
        $modelUser = model('User');
        $modelUserFromDb = $modelUser->getUserInfoFromDb(['openId' => $openId]);
        return $modelUserFromDb;
    }

    protected function wxLogin()
    {
        if (!empty(session('wx.gameInfo')))
            return session('wx.gameInfo');

        $weChat = controller('Wechat');
        $wx = session('wx');
        $openId = isset($wx['openId']) ? $wx['openId'] : '';
        if (empty($openId)) {
            $openId = $weChat->getOpenId();
        }

        if (empty($openId)) {
            $this->success('请先关注公众号...', self::$cm['weChatHomeUrl']);
            return '';
        }

        $modelUser = model('User');
        $wxUserInfo = $weChat->getUserInfo($openId);

        $modelUserFromDb = $modelUser->getUserInfoFromDb(['openId' => $openId]);
        if (!is_array($modelUserFromDb)) {
            $this->success('请先关注公众号...', self::$cm['weChatHomeUrl']);

            return '';
        }

        $modelUser = is_array($modelUserFromDb) ? array_merge($wxUserInfo, $modelUserFromDb) : $wxUserInfo;
        $modelUser['headimgurl'] = str_replace('http', 'https', $modelUser['headimgurl']);

        // halt([$wxUserInfo, $modelUserFromDb, session('wx')]);
        session('wx.gameInfo', $modelUser);
        return session('wx.gameInfo');

    }


}