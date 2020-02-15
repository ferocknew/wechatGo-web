<?php


namespace app\index\model;


class UserNotifyList extends Base
{
    protected $pk = 'id';

    protected $name = 'user_notify_list';

    public static function init()
    {
        Base::init();
    }

    /**
     * 一般 raw_creat_time  推荐用 mysql 的 now() 函数;
     * @param $data
     * @param $user_id
     * @return mixed
     */
    public function add($data, $user_id)
    {
        return self::$mydb->name('user_notify_list')->insert([
            'user_id' => $user_id,
            'notify_content' => $data['content'],
            'notify_titile' => '',
            'notify_level' => $data['rank'],
            'knock_time' => $data['time'],
            'repeat_flag' => $data['number'],
            'repeat_cront_str' => '',
            'raw_edit_time' => date('Y-m-d H:i:s'),
            'raw_creat_time' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getOneInfo($data, $user_id)
    {
        $where = [
            'knock_time' => $data['knock_time'],
            'user_id' => $user_id
        ];
        $res = self::$mydb->table('user_notify_list')->where($where)->find();
        // trace($res);
        return $res;
    }
}
