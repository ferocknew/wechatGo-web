<?php

namespace app\index\controller;


class Index extends Base
{

    private $eventWeChat = null;

    public function _initialize()
    {
        parent::_initialize();

    }

    public function index()
    {
        return "index";
    }

    public function addTasks()
    {
        $this->eventWeChat = new \app\index\event\Wechat();
        $this->eventWeChat->checkAuth();
        return $this->fetch('index');
    }


    public function tClear()
    {
        \think\session::clear();
    }
}