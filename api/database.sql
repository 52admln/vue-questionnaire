-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: 2017-05-12 21:47:03
-- 服务器版本： 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `questionare`
--

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`q_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '题目ID',AUTO_INCREMENT=6;
