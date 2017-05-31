-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: 2017-05-31 21:02:50
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
  `n_creattime` varchar(14) NOT NULL COMMENT '创建时间',
  `n_deadline` varchar(14) NOT NULL COMMENT '截止时间',
  `n_title` varchar(255) NOT NULL COMMENT '问卷标题',
  `n_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发布状态',
  `n_intro` text COMMENT '问卷介绍'
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='问卷表';

--
-- 转存表中的数据 `naire`
--

INSERT INTO `naire` (`n_id`, `a_id`, `n_creattime`, `n_deadline`, `n_title`, `n_status`, `n_intro`) VALUES
(22, 0, '1495628024980', '1496160000000', '杭州科技职业技术学院信息工程学院毕业生就业自我评价跟踪问卷调查表', 1, '同学，你好！\n为了能够更多地了解毕业生的信息，更好的服务于今后毕业生就业工作，学院特发出毕业生就业自我评价跟踪调查表，请如实填写。谢谢配合！');

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
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COMMENT='题目选项表';

--
-- 转存表中的数据 `options`
--

INSERT INTO `options` (`o_id`, `o_value`, `n_id`, `q_id`, `o_isaddtion`) VALUES
(74, '2013年6月', 22, 57, 0),
(75, '2014年6月', 22, 57, 0),
(76, '2015年6月', 22, 57, 0),
(77, '2016年6月', 22, 57, 0),
(78, '群众', 22, 58, 0),
(79, '共青团员', 22, 58, 0),
(80, '中共党员', 22, 58, 0),
(81, '其他', 22, 58, 0),
(82, '电子技术', 22, 59, 0),
(83, '电路与电工', 22, 59, 0),
(84, 'C语言', 22, 59, 0),
(85, 'Protel 制图', 22, 59, 0),
(86, 'CAD制图', 22, 59, 0),
(87, '单片机应用', 22, 59, 0),
(88, '嵌入式应用', 22, 59, 0),
(89, '电子产品工艺与管理', 22, 59, 0),
(90, '推荐', 22, 60, 0),
(91, '不推荐', 22, 60, 1);

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
  `q_description` text COMMENT '问题描述'
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='题目表';

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`q_id`, `q_content`, `q_type`, `n_id`, `q_isrequire`, `q_description`) VALUES
(56, '最近一份工作到岗时间', '文本', 22, 1, ''),
(57, '毕业时间', '单选', 22, 1, ''),
(58, '政治面貌', '单选', 22, 1, ''),
(59, '你认为哪些课程内容教学还需要加深？', '多选', 22, 1, ''),
(60, '你对母校是否推荐？', '单选', 22, 1, '不推荐的理由: （1）就业不好（2）师生交流不够（3）收费太高（4）学校管理太严（5）食堂服务与饭菜质量不好（6）专业辅导员不友好（7）住宿条件与管理不好（8）文娱体育设施不全（9）图书与教学实验设施不好（10）校风学风不好（11）校园偏僻交通不便');

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

--
-- 转存表中的数据 `result`
--

INSERT INTO `result` (`u_id`, `n_id`, `q_id`, `o_id`, `o_addtion`) VALUES
(1, 22, 56, 0, '2016.11.22'),
(1, 22, 57, 74, ''),
(1, 22, 58, 79, ''),
(1, 22, 59, 82, ''),
(1, 22, 59, 84, ''),
(1, 22, 59, 83, ''),
(1, 22, 59, 85, ''),
(1, 22, 60, 90, ''),
(1, 22, 56, 0, '2017.5.21'),
(1, 22, 57, 76, ''),
(1, 22, 58, 80, ''),
(1, 22, 59, 89, ''),
(1, 22, 59, 88, ''),
(1, 22, 59, 87, ''),
(1, 22, 59, 82, ''),
(1, 22, 59, 83, ''),
(1, 22, 59, 84, ''),
(1, 22, 59, 85, ''),
(1, 22, 59, 86, ''),
(1, 22, 60, 91, '就业不好'),
(1, 22, 56, 0, '2015.11.30'),
(1, 22, 57, 75, ''),
(1, 22, 58, 79, ''),
(1, 22, 59, 82, ''),
(1, 22, 59, 83, ''),
(1, 22, 59, 84, ''),
(1, 22, 59, 85, ''),
(1, 22, 59, 89, ''),
(1, 22, 60, 90, '');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_sex`, `u_class`, `u_number`, `u_identity`, `u_birthday`, `u_nation`, `u_password`, `u_major`) VALUES
(1, '陈程', 0, '计算机1505', '2015002529', '33062199611223015', '19961122', '汉族', 'a9c6272e1446483f94192d621f0c54fc0e92d70f', '计算机应用'),
(2, '王晶', 0, '计算机1506', '2015002419', '3306219951022301X', '19951022', '汉族', 'b3d5e79219be413507f545c70500c0a964392c3b', '计算机应用'),
(3, '兰亭', 0, '计算机1503', '2015002319', '33062199311022045', '19931102', '汉族', 'd7d3459c665337a01d64072505a0a217ae7a9fe3', '计算机应用');

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
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '问卷id',AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '题目ID',AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=4;
