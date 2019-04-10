<?php
/**
 * 商城最初的架构
 *
 * 这是最初 或者说最原始的框架模型
 *
 * 采用TP5的底层框架进行开发 严格按照MVC的格式进行相关的开发要求
 *
 * 前端的相关页面采用当前主流的响应式页面基于layui的样式库进行页面的再次开发
 *
 * 创建时 2019-3-23 20：07
 *
 * 创建人  张洪刚
 *
 * 创建目标  完成商城前后台的编写
 *
 * 页面内容包含相关的框架内容  数据表结果 和数据库写入
 */

/*******
****
***   此处为分割符号 进行上下内容的分割
***    分割上下文
***
*******/

/**
 * 开始链接数据库
 */
//检查php版本决定采用哪种链接方式ds
$php_version = PHP_VERSION;

$host = 'localhost'; //数据库链接地址

$username = 'root'; //数据库用户名

$padssword = ''; //数据库连接密码

$dbname = 'shopx'; //数据库名称


if($php_version >= 5.5.0){

    $connect = myslqi_connect($host,$username,$padssword,$dbname);

}else{

    $connect = myslqi_connect($host,$username,$padssword);

}
/*检测是否进行了正常链接*/
if(!$connect){

  die('Could not connect: '.myslq_error()); //不能连接输出结果

}

// 创建数据库函数语句为
$sql_insert_mysql = "CREATE DATABASE shopx DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
/**
 * 初始化后台 第一个数据表 菜单管理页面
 * 命名为   MENU
 *  相关字段为
 *
 *  编号：ID
 *  菜单名称：menu_name
 *  控制器： menu_controller
 *  方法：menu_actions
 *  上级ID： menu_pid
 *  上级名称: menu_pname
 *  菜单链接： menu_link
 *  排序ID： orderids
 *  创建时间： createtime
 *  状态：status
 */
CREATE TABLE IF NOT EXISTS `menu` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `menu_name` varchar(60) NOT NULL COMMENT '菜单名称',
  `menu_controller` varchar(60) NULL COMMENT '控制器',
  `menu_actions` varchar(60) NULL COMMENT '方法',
  `menu_link` varchar(200) NULL COMMENT '链接',
  `menu_pid`  int(10) DEFAULT '0' NOT NULL COMMENT '上级ID',
  `menu_pname` varchar(60) NULL COMMENT '上级菜单名称',
  `orderid` int(100) DEFAULT '0' NOT NULL COMMENT '排序ID',
  `createtime` int(13) NOT NULL COMMENT '创建修改时间',
  `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/**
 * 菜单相关字段创建完成
 *
 * 相关表字段 管理员管理
 *
 * 包含管理员增删改查  权限增删改查 管理员等级管理等
 *
 * 网站安全策略 和权限验证机制 采用 根据不同管理员角色 进行相关菜单的显示隐藏 进而控制管理员相关权限的处理 当然
 *
 * 针对特殊的情况 进行特殊的权限查询机制  以确保 相关权限的相关人员可以操控相关的操作
 *
 * 架构创建时间 2019-3-24 19：41
 *
 * 创建人 张洪刚
 */
/**
 * 管理员角色管理
 *
 * 角色权限管控
 *
 * 角色ID  ID
 *
 * 角色名称 rule_name
 *
 * 角色描述 rule_des
 *
 * 角色图片 rule_pic
 *
 * 角色控制权限 rule_menu (以JSON字符串的形式 进行菜单权限的判断 进而实现权限的控制)
 *
 * 角色创建时间  createtime
 *
 * 角色排序ID   orderid
 *
 * 角色状态 Status
 */
 CREATE TABLE IF NOT EXISTS `rule` (
   `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色id',
   `rule_name` varchar(30) NOT NULL COMMENT '角色名称',
   `rule_des` varchar(255) NOT NULL COMMENT '角色描述',
   `rule_pic` varchar(255) NOT NULL COMMENT '角色图片',
   `rule_menu` text NOT NULL COMMENT '角色权限',
   `createtime` int(13) NOT NULL COMMENT '创建修改时间',
   `orderid` int(100) DEFAULT '0' NOT NULL COMMENT '排序ID',
   `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
   PRIMARY KEY (`id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

 /**
  * 管理员管理
  *
  * 根据管理员角色 可进行管理员的创建和分类
  *
  * 管理员可为 开发级管理员 只有开发者具有该账号 可以操作所有权限 其次是通过开发者创建的相关管理员账户 这些账户均不具备对角色权限进行调整的权力
  *
  * 管理员ID  ID
  *
  * 管理员名称 admin_name
  *
  * 管理员描述 admin_des
  *
  * 管理员头像 admin_pic
  *
  * 管理员账号 admin_num
  *
  * 管理员密码 admin_pass
  *
  * 管理员密码加密随机字符串 admin_pass_key
  *
  * 管理员角色 admin_role
  *
  * 是否为开发级管理  admin_key
  *
  * 登录次数  admin_login_num
  *
  * 登录IP  admin_login_ip
  *
  * 上次登录时间 admin_login_time
  *
  * 登录密码失败次数 admin_login_false_num
  *
  * 创建时间 createtime
  *
  * 状态 status
  */
  CREATE TABLE IF NOT EXISTS `admin` (
    `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员id',
    `admin_name` varchar(30) NOT NULL COMMENT '管理员名称',
    `admin_des` varchar(255) NULL COMMENT '管理员描述',
    `admin_pic` varchar(255) NULL COMMENT '管理员头像',
    `admin_num` varchar(255) NOT NULL COMMENT '管理员账号',
    `admin_pass` varchar(255) NOT NULL COMMENT '管理密码',
    `admin_pass_key` varchar(30) NOT NULL COMMENT '管理员密钥加密手段',
    `admin_role` int(10) NOT NULL COMMENT '管理员角色',
    `admin_key` int(1) DEFAULT '0' NOT NULL COMMENT '是否是开发级',
    `admin_login_num` int(10) DEFAULT '0' NOT NULL COMMENT '登录次数',
    `admin_login_id` varchar(30) NULL COMMENT '登录IP',
    `admin_login_time` int(13) NOT NULL COMMENT '上次登录时间',
    `admin_login_false_num` int(1) NOT NULL COMMENT '登录密码失败次数',
    `createtime` int(13) NOT NULL COMMENT '创建修改时间',
    `orderid` int(100) DEFAULT '0' NOT NULL COMMENT '排序ID',
    `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/**
 * 栏目管理
 *
 * 本栏目管理旨在管理相关网站的栏目  用于对网站前台的内容进行分类和展示
 *
 * 可以进行栏目的添加、修改、删除 （后台默认设定栏目的分类）
 *
 * 栏目可以分为 图文、文章、视频、下载、留言、招聘、单页 分别对应
 *
 * 相关字段
 * 栏目ID  id
 * 族谱 pstr
 * 栏目名称 name
 * 栏目标志 act
 * 栏目别名 re_name
 * 栏目链接 url
 * 上级栏目ID parentid
 * 栏目类型 type(0123456)
 * 栏目状态 status 显示与隐藏
 * 创建时间 createtime
 * 优化标题 seo_title
 * 优化关键词 seo_keywords
 * 优化描述 seo_description
 */
 CREATE TABLE IF NOT EXISTS `cate` (
   `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
   `pid` int(5) DEFAULT '0' NOT NULL COMMENT '上级ID',
   `pstr` varchar(255) DEFAULT '0,' NOT NULL COMMENT '族谱',
   `type` int(1) DEFAULT '0' NOT NULL COMMENT '栏目类型，默认为单页',
   `name` varchar(30) NOT NULL COMMENT '栏目名',
   `act` varchar(30) NULL COMMENT '栏目标志',
   `re_name` varchar(30) NULL COMMENT '别名',
   `url` varchar(255) NULL COMMENT '栏目链接',
   `seo_title` varchar(30) NULL COMMENT 'seo标题',
   `seo_keywords` varchar(100) NULL COMMENT 'seo关键词',
   `seo_description` varchar(255) NULL COMMENT 'seo描述',
   `createtime` int(13) NOT NULL COMMENT '创建修改时间',
   `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
   PRIMARY KEY (`id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/**
 *
 * 内容管理 分类单页管理和图文管以及 职位、下载等管理
 *
 * 创建时间 2019-4-10 19：53
 *
 * actor xingkong
 *
 * 图文列表页  包含单页+文章+图文全部使用 图文表--artice
 *
 * 图文列表相关字段
 *
 * 图文ID  id
 * 栏目ID cate_id
 * 族谱 cate_str
 * 类型 type 默认为单页 还有文章 图文  等等
 * 图文名称 name
 * 图文标志 act
 * 图文别名 re_name
 * 图片链接 imgurl
 * 组图 imgarr
 * 摘要 description
 * 详情 congtent
 * 作者 actor
 * 点击量 hit
 * 文章链接 url
 * 是否推荐 is_tj
 * 是否删除 is_del
 * 删除时间 deletetime
 * 状态 status
 * 创建时间 createtime
 * seo标题 seo_title
 * seo关键词 seo_keywords
 * seo描述 seo_description
 * 排序ID orderid
 */
 CREATE TABLE IF NOT EXISTS `artice` (
   `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '单页ID',
   `cate_id` int(5) DEFAULT '0' NOT NULL COMMENT '栏目ID',
   `cate_str` varchar(255) DEFAULT '0,' NOT NULL COMMENT '族谱',
   `type` int(1) DEFAULT '0' NOT NULL COMMENT '栏目类型，默认为单页',
   `name` varchar(30) NOT NULL COMMENT '栏目名',
   `act` varchar(30) NULL COMMENT '栏目标志',
   `re_name` varchar(30) NULL COMMENT '别名',
   `description` varchar(255) NULL COMMENT '摘要',
   `imgurl` varchar(255) NULL COMMENT '图片链接',
   `imgarr` text NULL COMMENT '组图',
   `actor` varchar(30) NULL COMMENT '作者',
   `url` varchar(255) NULL COMMENT '图文链接',
   `hit` int(10) DEFAULT '0' NOT NULL COMMENT '点击量',
   `content` text NULL COMMENT '详情',
   `is_tj` int(1) DEFAULT '0' NOT NULL COMMENT '是否推荐 默认为不推荐',
   `is_dle` int(1) DEFAULT '0' NOT NULL COMMENT '是否删除',
   `deletetime` int(13) NULL COMMENT '删除时间',
   `seo_title` varchar(30) NULL COMMENT 'seo标题',
   `seo_keywords` varchar(100) NULL COMMENT 'seo关键词',
   `seo_description` varchar(255) NULL COMMENT 'seo描述',
   `createtime` int(13) NOT NULL COMMENT '创建修改时间',
   `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
   PRIMARY KEY (`id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

 /**
  * 招聘列表相关  招聘---job
  *
  * 相关数据表字段
  *
  * 职位ID id
  * 栏目ID cate_id
  * 族谱 cate_str
  * 职位名称 name
  * 职位简介 description
  * 招聘海报 imgurl
  * 职位链接 url
  * 工作地点 addr
  * 招聘人数 num
  * 招聘时间  job_time
  * 招聘地点 job_addr
  * 职位要求 content
  * 发布人 actor
  * 状态 Status
  * 创建时间 createtime
  * seo标题 seo_title
  * seo关键词 seo_keywords
  * seo描述 seo_description
  */
  CREATE TABLE IF NOT EXISTS `artice` (
    `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '职位ID',
    `cate_id` int(5) DEFAULT '0' NOT NULL COMMENT '栏目ID',
    `cate_str` varchar(255) DEFAULT '0,' NOT NULL COMMENT '族谱',
    `name` varchar(30) NOT NULL COMMENT '职位名称',
    `description` varchar(255) NULL COMMENT '简介',
    `imgurl` varchar(255) NULL COMMENT '招聘海报',
    `actor` varchar(30) NULL COMMENT '发布人',
    `addr` varchar(255) NULL COMMENT '工作地点',
    `job_addr` varchar(255) NULL COMMENT '招聘地点',
    `job_time` int(13) NULL COMMENT '招聘时间',
    `url` varchar(255) NULL COMMENT '职位链接',
    `num` int(10) DEFAULT '0' NOT NULL COMMENT '招聘人数',
    `content` text NULL COMMENT '职位要求',
    `seo_title` varchar(30) NULL COMMENT 'seo标题',
    `seo_keywords` varchar(100) NULL COMMENT 'seo关键词',
    `seo_description` varchar(255) NULL COMMENT 'seo描述',
    `createtime` int(13) NOT NULL COMMENT '创建修改时间',
    `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  /**
   * 下载相关列表  下载 download
   *
   * 相关表字段
   *
   * 下载ID id
   * 栏目ID cate_id
   * 族谱 cate_str
   * 下载名称 name
   * 下载内容简介 description
   * 下载封面图 imgurl
   * 下载链接 url
   *  下载量 hit
   * 详情 content
   * 发布人 actor
   * 状态 Status
   * 创建时间 createtime
   * seo标题 seo_title
   * seo关键词 seo_keywords
   * seo描述 seo_description
   */
   CREATE TABLE IF NOT EXISTS `artice` (
     `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '单页ID',
     `cate_id` int(5) DEFAULT '0' NOT NULL COMMENT '栏目ID',
     `cate_str` varchar(255) DEFAULT '0,' NOT NULL COMMENT '族谱',
     `name` varchar(30) NOT NULL COMMENT '下载标题',
     `description` varchar(255) NULL COMMENT '简介',
     `imgurl` varchar(255) NULL COMMENT '封面图',
     `actor` varchar(30) NULL COMMENT '发布人',
     `url` varchar(255) NULL COMMENT '下载链接',
     `hit` int(10) DEFAULT '0' NOT NULL COMMENT '点击量',
     `content` text NULL COMMENT '详情',
     `seo_title` varchar(30) NULL COMMENT 'seo标题',
     `seo_keywords` varchar(100) NULL COMMENT 'seo关键词',
     `seo_description` varchar(255) NULL COMMENT 'seo描述',
     `createtime` int(13) NOT NULL COMMENT '创建修改时间',
     `stauts` int(1) DEFAULT '0' NOT NULL COMMENT '状态 0为正常 1为禁用 2为隐藏',
     PRIMARY KEY (`id`)
   ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/**
 * 至此 内容页相关字段结束  下面进入会员管理的相关数据表  包含  会员等级 管理  会员充值管理  会员体现管理  会员积分管理 会员列表管理 等等
 *
 * 创建时间 2019-4-10 20：38
 *
 * actor xingkong
 *
 * @xingkongwuhen@sina.cn
 *
 * 会员登记表 member_type
 *
 * 会员表 member
 *
 * 会员充值记录表 member_charge
 *
 * 会员充值类别表 charge_type
 *
 * 会员积分使用表 member_point
 */




 ?>
