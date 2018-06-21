/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : ecshop

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-06-21 17:12:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ecs_custom_company
-- ----------------------------
DROP TABLE IF EXISTS `ecs_custom_company`;
CREATE TABLE `ecs_custom_company` (
  `company_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(50) NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ecs_custom_dept
-- ----------------------------
DROP TABLE IF EXISTS `ecs_custom_dept`;
CREATE TABLE `ecs_custom_dept` (
  `dept_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(50) NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ecs_custom_employee
-- ----------------------------
DROP TABLE IF EXISTS `ecs_custom_employee`;
CREATE TABLE `ecs_custom_employee` (
  `employee_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) unsigned NOT NULL,
  `dept_id` int(11) unsigned NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `id_card` varchar(20) DEFAULT NULL,
  `sex` varchar(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `reg_time` date DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ecs_custom_employee_user
-- ----------------------------
DROP TABLE IF EXISTS `ecs_custom_employee_user`;
CREATE TABLE `ecs_custom_employee_user` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
