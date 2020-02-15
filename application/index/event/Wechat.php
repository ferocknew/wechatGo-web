<?php

namespace app\index\event;
class Wechat extends Base
{

    public function _initialize()
    {
        Base::_initialize();
    }

    public function checkAuth()
    {
        if (empty(session('user'))) {
            $wx = new \app\index\controller\Wechat();
            $wx->oauth();
        }

        return;
    }
}