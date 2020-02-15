<?php

namespace app\index\controller;


class Index extends Base
{

    public function _initialize()
    {
        parent::_initialize();
        checkAuth();
    }

    public function index()
    {
        return $this->fetch('index');
    }

    public function tClear()
    {
        \think\session::clear();
    }
}