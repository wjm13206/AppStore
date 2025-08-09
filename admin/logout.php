<?php
// 后台登出页面
session_start();
require_once '../config.php';

// 清除所有session变量
$_SESSION = array();

// 销毁session
session_destroy();

// 重定向到登录页面
header("Location: login.php");
exit;
?>