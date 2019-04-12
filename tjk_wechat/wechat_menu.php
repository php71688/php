<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/10
 * Time: 15:01
 */
include_once "wechat.class.php";

$wechat=new Me_wx();

$list=$wechat->set_menu();

/*var_dump($list);*/