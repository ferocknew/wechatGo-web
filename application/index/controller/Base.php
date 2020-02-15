<?php

namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Session;

// 引入类库

class Base extends Controller
{

    public static $post = null, $get = null, $route = null, $auth = null;
    public static $session = null;
    public static $c = null;
    public static $cm = null;
    public static $m = null;
    private static $r = null;
    protected static $configPath = '';

    /**
     * @var bool
     */
    private static $sessionInitFlag = false;

    public function _initialize()
    {
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
        self::$configPath = CONF_PATH . DS . 'extra' . DS . self::$m . DS;

//        if (!isset($_SESSION)) {
        $sessionConfig = \think\Config::parse(self::$configPath . 'session_config.ini', 'ini')['session_config'];
        $this->sessionInit($sessionConfig);
//        }

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

    private function sessionInit($sessionConfig = [])
    {
        if (empty($sessionConfig)) {
            throw new Exception("session config error!");
            // return false;
        }
        // trace(["sessionInit" => time(), 'self::$sessionInitFlag' => self::$sessionInitFlag], 'error');

        try {
            if (!self::$sessionInitFlag)
                Session::init($sessionConfig);

            self::$sessionInitFlag = true;
        } catch (Exception $e) {
            trace(["Session Init error" => $e->getMessage()], "error");
        }

        return true;
    }
}