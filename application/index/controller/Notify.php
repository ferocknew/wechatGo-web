<?php

namespace app\index\controller;

use app\index\model\UserNotifyList;

class Notify extends Base
{
    private $eventWeChat = null;
    private static $userId = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->eventWeChat = new \app\index\event\Wechat();
        $this->eventWeChat->checkAuth();
        if (!empty(session('user')['user_id'])) self::$userId = session('user')['user_id'];
    }

    public function add()
    {
        try {
            $data = self::$post;
            if (empty(self::$userId)) return rtJson(1, '创建提醒失败');

            $validate = new \app\index\validate\Notify();
            $result = $validate->scene('add')->check($data);
            if ($result != true) {
                return rtJson(1, $validate->getError());
            }

            // trace(['session(\'user\')' => session('user')], 'info');


            $modelUserNotifyList = new UserNotifyList();
            $repeatFlag = getValue($data, 'number', 0);

            // 去除重复提醒
            if ($repeatFlag == 1)
                $epeatedrRows = $modelUserNotifyList->getOneInfo(['knock_time' => $data['time']], self::$userId);
            if (!empty($epeatedrRows)) return rtJson(1, '这个时间有重复的提醒');


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
