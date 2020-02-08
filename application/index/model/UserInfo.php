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
        // return $this->allowField(['open_id', 'wx_nickname', 'user_avatar', 'wx_appid'])->save($data);
    }

    public function updateUserInfo($data, $id)
    {
        // return $this->allowField(['wx_nickname', 'user_avatar', 'wx_appid'])->save($data, ['id' => $id]);
    }

    public function getUserInfo($openId = '')
    {
        $field = ['id', 'open_id', 'wx_nickname', 'user_avatar', 'wx_appid'];
        $rs = self::$mydb->table('user_info')->field($field)->where('open_id', $openId)->find();
        return $rs;
    }
}