CREATE TABLE `user_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信 id',
  `wx_nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `user_avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `wx_appid` varchar(255) NOT NULL DEFAULT '' COMMENT '微信AppId，区别不同的微信公众号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

CREATE TABLE `user_notify_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delete_flag` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '软删除标记',
  `raw_creat_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '数据创建时间',
  `raw_edit_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '数据修改时间',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'user_info 表 ID',
  `notify_content` varchar(1000) NOT NULL DEFAULT '' COMMENT '提醒内容',
  `notify_titile` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒标题，本期不需要',
  `notify_level` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '提醒级别，1~9 数字越大级别越高',
  `knock_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '提醒时间，一次性提醒使用',
  `repeat_flag` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否重复提醒',
  `repeat_cront_str` varchar(50) NOT NULL DEFAULT '' COMMENT '重复提醒的数据格式，* * * * * * ，6位cront 支持到秒',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
