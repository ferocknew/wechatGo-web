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
        $this->config['secret'] = $this->config['app_secret'];

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

        $string = file_get_contents(self::$configPath . 'wecahtMenu.json');
        $string = json_decode($string, true);

        $app = Factory::officialAccount($this->config);

        $result = $app->menu->delete();           // 删除全部菜单
        if ($result['errcode'] !== 0) {
            return $result['errmsg'];
        }
        $result = $app->menu->create($string);   // 设置新菜单
        if ($result['errcode'] !== 0) {
            return $result['errmsg'];
        }

        return 'success';
    }

    /**
     * 微信公众号菜单/按钮 操作
     * @return mixed|string|null
     */
    public function wechatMenu()
    {
        $app = Factory::officialAccount($this->config);
        // 全部菜单
        $list = $app->menu->list();
        // 菜单不存在设置默认菜单
        if (isset($list['errcode'])) {
            $string = file_get_contents(self::$configPath . 'wecahtMenu.json');
            $menuDemo = json_decode($string, true);
            $addRes = $app->menu->create($menuDemo);   // 设置默认菜单
            if ($addRes['errcode'] !== 0) {
                return $addRes['errmsg'];
            }
            $list = $app->menu->list();
        }
        // 菜单类型
        $type = config("configMENU.menu_type");
        // 菜单验证码
        $menuPwd = config("configMENU.menu_pwd");
        if (request()->isPost()) {
            $param = request()->param();
            $comArr = [];
            $levelArr = $param['level'];
            foreach ($levelArr as $key => $value) {
                foreach ($param as $k => $v) {
                    $comArr[$key][$k] = $v[$key];
                }
                $comArr[$key] = array_filter($comArr[$key]);
            }
            $buttons = [];
            $num = -1;
            foreach ($comArr as $key => $value) {
                $level = $value['level'];
                unset($value['level']);
                if ($level == 'first') {
                    $num += 1;
                    $buttons[] = $value;
                } else {
                    $buttons[$num]['sub_button'][] = $value;
                }
            }
            $result = $app->menu->create($buttons);   // 设置新菜单
            return $result['errcode'] === 0 ? 'success' : $result['errmsg'];
        }
        $menuList = getValue($list['menu'], 'button', []);
        $this->assign([
            'menuPwd' => $menuPwd,
            'typeArr' => $type,
            'list' => $menuList,
        ]);
        return $this->fetch('menu');
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

    public function oauth()
    {

        $this->config['oauth'] = [
            'scopes' => ['snsapi_userinfo'],
            'callback' => request()->domain() . '/index/Wechat/oauthCallback',
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

        trace($user->toArray());

        $modelUserInfo = new UserInfo;
        // 这个逻辑也放到 model ，只需暴露一个fn 出来就行了，controller 不要放任何数据操作。
        $userInfo = $modelUserInfo->getUserInfo($user->getId());

        $data = [
            'wx_appid' => $this->config['app_id'],
            'open_id' => $user->getId(),
            'wx_nickname' => $user->getNickname(),
            'user_avatar' => $user->getAvatar(),
        ];

        if ($userInfo == null) {
            $userInfo->addUser($data);
        } else {
            $userInfo->updateUserInfo($data, $userInfo->id);
        }

        session('user', $data);

        header('location:' . request()->domain());
    }

    public function test()
    {
        $modelUserInfo = new UserInfo;
        dump($modelUserInfo->getUserInfo('oFpU71WOVyyACGEBAwQehUkg5W3E'));

        $sessionValue = session("aaa");
        if (empty($sessionValue)) {
            echo "存储 session";
            session("aaa", '123');
        } else {
            echo "读取session";
            return $sessionValue;
        }

    }

    public function server()
    {
        $app = Factory::officialAccount($this->config);

        $response = $app->server->serve();

        $response->send();exit; 
        // return $response;
    }
}