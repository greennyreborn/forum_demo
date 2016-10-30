CREATE DATABASE IF NOT EXISTS `forum`;

CREATE TABLE IF NOT EXISTS `forum`.`user` (
  `id` INT(10) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `uid` BIGINT(20) unsigned NOT NULL,
  `username` VARCHAR(64) NOT NULL,
  `avatar` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像url',
  `password` VARCHAR(32) NOT NULL,
  `created_at` INT(10) unsigned NOT NULL,
  `updated_at` INT(10) unsigned NOT NULL,
  INDEX `INDEX_UID` (`uid`),
  INDEX `INDEX_USERNAME` (`username`),
  INDEX `INDEX_CREATED_AT` (`created_at`)
);

CREATE TABLE IF NOT EXISTS `forum`.`topic` (
  `id` INT(10) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `topic_id` BIGINT(20) unsigned NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `type` TINYINT(4) unsigned NOT NULL DEFAULT 1 COMMENT '主题类型',
  `content` LONGTEXT NOT NULL,
  `uid` BIGINT(20) unsigned NOT NULL,
  `last_edit_time` INT(10) unsigned NOT NULL,
  `last_reply_time` INT(10) unsigned NOT NULL,
  `reply_num` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '回复数',
  `last_reply_uid` BIGINT(20) unsigned NOT NULL DEFAULT 0 COMMENT '最后回复uid',
  `created_at` INT(10) unsigned NOT NULL,
  `updated_at` INT(10) unsigned NOT NULL,
  INDEX `INDEX_TOPIC_ID` (`topic_id`),
  INDEX `INDEX_TITLE` (`title`),
  INDEX `INDEX_UID` (`uid`),
  INDEX `INDEX_TYPE` (`type`),
  INDEX `INDEX_LAST_REPLY` (`last_reply_time`)
);

CREATE TABLE IF NOT EXISTS `forum`.`post` (
  `id` INT(10) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) unsigned NOT NULL,
  `content` LONGTEXT NOT NULL,
  `topic_id` BIGINT(20) unsigned NOT NULL,
  `uid` BIGINT(20) unsigned NOT NULL,
  `last_edit_time` INT(10) unsigned NOT NULL,
  `created_at` INT(10) unsigned NOT NULL,
  `updated_at` INT(10) unsigned NOT NULL,
  INDEX `INDEX_POST_ID` (`post_id`),
  INDEX `INDEX_TOPIC_ID` (`topic_id`),
  INDEX `INDEX_UID` (`uid`),
  INDEX `INDEX_CREATED_AT` (`created_at`)
);
