<?php
// 侧边栏组件
?>
<!--  layui.css -->
<link href="//unpkg.com/layui@2.11.5/dist/css/layui.css" rel="stylesheet"></div>
<!--  layui.js -->
<script src="//unpkg.com/layui@2.11.5/dist/layui.js"></script></div>
<div class="layui-side layui-bg-black" style="width: 250px;">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree" lay-filter="side">
            <li class="layui-nav-item layui-nav-title">应用商店</li>
            <li class="layui-nav-item">
                <a href="index.php">
                    <i class="layui-icon layui-icon-console"></i>控制台
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="apps.php">
                    <i class="layui-icon layui-icon-app"></i>应用管理
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="categories.php">
                    <i class="layui-icon layui-icon-tabs"></i>分类管理
                </a>
            </li>
            <li class="layui-nav-item" style="margin-top: 20px;">
                <a href="logout.php" style="color: #FF5722;">
                    <i class="layui-icon layui-icon-logout"></i>退出登录
                </a>
            </li>
        </ul>
    </div>
</div>