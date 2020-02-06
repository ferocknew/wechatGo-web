<?php

namespace app\index\model;

use think\Model;

class Base extends Model
{
    protected static $c = null;
    protected static $cm = null;
    protected static $m = null;
    protected static $mydb = null;

    public static function init()
    {
        // self::$bcLength = config('base.bcLength');
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
        $dbConfig = \think\Config::parse(CONF_PATH . DS . 'extra' . DS . self::$m . DS . 'database.ini', 'ini')['database'];
        \bcscale(self::$cm['bcLength']);
        trace(['$dbConfig' => $dbConfig]);

        // 暂时不使用数据库
        // self::$mydb = Db::connect([
        //    // 数据库类型
        //    'type'        => 'mysql',
        //    // 数据库连接DSN配置
        //    'dsn'         => '',
        //    // 服务器地址
        //    'hostname'    => '127.0.0.1',
        //    // 数据库名
        //    'database'    => 'thinkphp',
        //    // 数据库用户名
        //    'username'    => 'root',
        //    // 数据库密码
        //    'password'    => '',
        //    // 数据库连接端口
        //    'hostport'    => '',
        //    // 数据库连接参数
        //    'params'      => [],
        //    // 数据库编码默认采用utf8
        //    'charset'     => 'utf8',
        //    // 数据库表前缀
        //    'prefix'      => 'think_',
        //]);

    }


}