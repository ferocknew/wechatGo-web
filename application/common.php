<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


function getValue($arr = [], $key = '', $default = '')
{
    $returnData = null;
    $returnData = isset($arr[$key]) ? $arr[$key] : $default;
    return $returnData;
}

function checkAuth()
{
    if (empty(session('user'))) {
        $wx = new app\index\controller\Wechat();
        $wx->oauth();
    }
}

function rtJson($code = 1, $msg = '', $data = [])
{
    return json(
        [
            'code' => $code,
            'msg'  => $msg,
            'data' => $code == 0 ? $data : [],
        ]
    );
}
