<?php

namespace app\index\controller;

use app\index\model\UserNotifyList;

class Notify extends Base
{
    private $eventWeChat = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->eventWeChat = new \app\index\event\Wechat();
        $this->eventWeChat->checkAuth();
    }

    public function add()
    {
        try {
            $data = self::$post;

            $validate = new \app\index\validate\Notify();
            $result = $validate->scene('add')->check($data);
            if ($result != true) {
                return rtJson(1, $validate->getError());
            }

            // trace(['session(\'user\')' => session('user')], 'info');

            $modelUserNotifyList = new UserNotifyList();
            $result = $modelUserNotifyList->add($data, session('user')['user_id']);

            if ($result != true) {
                return rtJson(1, '创建提醒失败');
            }

            return rtJson(1, '创建提醒成功');
        } catch (\Throwable $th) {
            trace('error: ' . $th->getMessage(), 'error');
            return rtJson(1, '创建提醒失败');
        }
    }
}
