<?php

namespace app\index\validate;

use think\Validate;

class Notify extends Validate
{
    protected $rule = [
        'content' => 'require|max:600',
        'time' => 'require|dateFormat:Y-m-d H:i|checkTime',
        'rank' => 'require|number',
        'number' => 'require|number',
        'method' => 'require|number',
    ];

    protected $message = [
        'content.require' => '请填写内容',
        'content.max' => '内容最多不能超过600个字符',
        'time.require' => '请选择提醒时间',
        'time.dateFormat' => '提醒时间格式错误',
        'rank.require' => '请选择提醒级别',
        'rank.number' => '提醒级别错误',
        'number.require' => '请选择提醒次数',
        'number.number' => '提醒次数错误',
        'method.require' => '请选择提醒方式',
        'method.number' => '提醒方式错误',
    ];

    protected $scene = [
        'add' => ['content', 'time', 'rank', 'number', 'method'],
    ];

    protected function checkTime($value)
    {
        return strtotime($value) > time() ? true : '提醒时间必须大于当前时间';
    }
}
