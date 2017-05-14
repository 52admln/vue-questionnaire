-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: 2017-05-14 14:51:26
-- 服务器版本： 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `questionare`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL COMMENT '管理员ID',
  `a_username` varchar(25) NOT NULL COMMENT '用户名',
  `a_password` varchar(40) NOT NULL COMMENT '密码'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`a_id`, `a_username`, `a_password`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- --------------------------------------------------------

--
-- 表的结构 `naire`
--

CREATE TABLE `naire` (
  `n_id` int(11) NOT NULL COMMENT '问卷id',
  `a_id` int(11) NOT NULL COMMENT '管理员id',
  `n_creattime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `n_deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '截止时间',
  `n_title` varchar(255) NOT NULL COMMENT '问卷标题',
  `n_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发布状态',
  `n_intro` text COMMENT '问卷介绍'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='问卷表';

--
-- 转存表中的数据 `naire`
--

INSERT INTO `naire` (`n_id`, `a_id`, `n_creattime`, `n_deadline`, `n_title`, `n_status`, `n_intro`) VALUES
(1, 1, '2017-05-14 06:32:34', '2017-05-30 16:00:00', '问卷1', 0, '问卷介绍11111'),
(2, 1, '2017-05-14 06:32:34', '2017-04-30 16:00:00', '问卷2', 0, '问卷介绍22222');

-- --------------------------------------------------------

--
-- 表的结构 `options`
--

CREATE TABLE `options` (
  `o_id` int(11) NOT NULL COMMENT '选项ID',
  `o_value` text NOT NULL COMMENT '选项内容',
  `n_id` int(11) NOT NULL COMMENT '问卷ID',
  `q_id` int(11) NOT NULL COMMENT '题目ID',
  `o_isaddtion` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有附加内容'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='题目选项表';

--
-- 转存表中的数据 `options`
--

INSERT INTO `options` (`o_id`, `o_value`, `n_id`, `q_id`, `o_isaddtion`) VALUES
(1, '选项1', 1, 1, 1),
(2, '选项2', 1, 1, 0),
(3, '选项33', 1, 2, 0),
(4, '选项44', 1, 2, 0);

-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE `question` (
  `q_id` int(11) NOT NULL COMMENT '题目ID',
  `q_content` text NOT NULL COMMENT '题目内容',
  `q_type` varchar(10) NOT NULL COMMENT '题目类型（单选、多选、填空）',
  `n_id` int(11) NOT NULL COMMENT '问卷ID',
  `q_isrequire` tinyint(1) NOT NULL COMMENT '是否为必填项',
  `q_description` text NOT NULL COMMENT '问题描述'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='题目表';

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`q_id`, `q_content`, `q_type`, `n_id`, `q_isrequire`, `q_description`) VALUES
(1, '题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1', '单选', 1, 1, ''),
(2, '多选题1多选题1多选1题多选1题多选题多1选题多选题多选题1多选题1', '多选', 1, 0, '问题描述11111111'),
(5, '文本题目11文本题目文本题111目文本11题目文本题目文本题目222', '文本', 1, 1, '问题描述11111111');

-- --------------------------------------------------------

--
-- 表的结构 `result`
--

CREATE TABLE `result` (
  `u_id` int(11) NOT NULL COMMENT '用户ID',
  `n_id` int(11) NOT NULL COMMENT '问卷ID',
  `q_id` int(11) NOT NULL COMMENT '题目ID',
  `o_id` int(11) DEFAULT NULL COMMENT '选项ID',
  `o_addtion` text COMMENT '附加文字'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL COMMENT '用户ID',
  `u_name` varchar(100) NOT NULL COMMENT '姓名',
  `u_sex` int(1) NOT NULL COMMENT '性别',
  `u_class` varchar(255) NOT NULL COMMENT '班级',
  `u_number` char(10) NOT NULL COMMENT '学号',
  `u_identity` varchar(18) NOT NULL COMMENT '身份证',
  `u_birthday` varchar(8) NOT NULL COMMENT '出生日期',
  `u_nation` varchar(10) NOT NULL COMMENT '民族',
  `u_password` varchar(40) NOT NULL COMMENT '密码',
  `u_major` varchar(200) NOT NULL COMMENT '专业名称'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_sex`, `u_class`, `u_number`, `u_identity`, `u_birthday`, `u_nation`, `u_password`, `u_major`) VALUES
(1, '陈程', 0, '计算机1505', '2015002529', '33062199611223015', '19961122', '汉族', 'a9c6272e1446483f94192d621f0c54fc0e92d70f', '计算机应用'),
(2, '王晶', 1, '计算机1506', '2015002419', '3306219951022301X', '19951022', '汉族', 'b3d5e79219be413507f545c70500c0a964392c3b', '计算机应用'),
(4, '兰亭', 0, '计算机1503', '2015002319', '33062199311022045', '19931102', '汉族', 'd7d3459c665337a01d64072505a0a217ae7a9fe3', '计算机应用');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `naire`
--
ALTER TABLE `naire`
  ADD PRIMARY KEY (`n_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`o_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`q_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `naire`
--
ALTER TABLE `naire`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '问卷id',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '题目ID',AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=5;
