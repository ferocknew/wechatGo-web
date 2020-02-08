<?php


namespace app\index\model;


class UserInfo extends Base
{
    protected $pk = 'id';

    protected $name = 'user_info';

    public function addUser($data)
    {
        return $this->allowField(['open_id', 'wx_nickname', 'user_avatar', 'wx_appid'])->save($data);
    }

    public function updateUserInfo($data, $id)
    {
        return $this->allowField(['wx_nickname', 'user_avatar', 'wx_appid'])->save($data, ['id' => $id]);
    }
}