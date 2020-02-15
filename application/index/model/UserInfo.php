<?php


namespace app\index\model;


class UserInfo extends Base
{
    protected $pk = 'id';

    protected $name = 'user_info';

    public static function init()
    {
        Base::init();
    }


    public function addUser($data)
    {
        return self::$mydb->name('user_info')->insert([
            'open_id'       => $data['open_id'],
            'wx_nickname'   => $data['wx_nickname'],
            'user_avatar'   => $data['user_avatar'],
            'wx_appid'      => $data['wx_appid']
        ]);
    }

    public function updateUserInfo($data, $id)
    {
        return self::$mydb->name('user_info')
            ->where('id', $id)
            ->update([
                'wx_nickname'   => $data['wx_nickname'],
                'user_avatar'   => $data['user_avatar'],
                'wx_appid'      => $data['wx_appid'],
            ]);
    }

    public function getUserInfo($openId = '')
    {
        $field = ['id', 'open_id', 'wx_nickname', 'user_avatar', 'wx_appid'];
        $rs = self::$mydb->table('user_info')->field($field)->where('open_id', $openId)->find();
        return $rs;
    }
}
