<?php

namespace app\index\controller;


class Index extends Base
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->wxLogin();

        return $this->fetch('index');
    }
}