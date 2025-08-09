<?php
// 后台管理首页
session_start();
require_once '../config.php';

// 是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 后台管理</title>
    <link rel="stylesheet" href="//unpkg.com/layui@2.11.5/dist/css/layui.css">
    <style>
        .layui-layout-admin .layui-side {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="layui-layout layui-layout-admin">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content">
            <h2>后台管理首页</h2>
            <hr>
<!--             
I just wanna be left alone
我只想一个人独处
Just wanna be left alone
我只想一个人独处
I feel it in my bones
发自内心的愿望
Feel it in my bones
发自内心的愿望 -->
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">应用总数</div>
                        <div class="layui-card-body">
                            <h1><?php echo getTotalApps(); ?></h1>
                        </div>
                    </div>
                </div>
                
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">分类总数</div>
                        <div class="layui-card-body">
                            <h1><?php echo getTotalCategories(); ?></h1>
                        </div>
                    </div>
                </div>
                
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">总下载量</div>
                        <div class="layui-card-body">
                            <h1><?php echo getTotalDownloads(); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="layui-card" style="margin-top: 20px;">
                <div class="layui-card-header">系统信息</div>
                <div class="layui-card-body">
                    <p>PHP版本: <?php echo PHP_VERSION; ?></p>
                    <p>服务器软件: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? '未知'; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="//unpkg.com/layui@2.11.5/dist/layui.js"></script>
</body>
</html>