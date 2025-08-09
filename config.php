<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
 * 应用商店配置文件
 * 最后更新：2025-7-20 
 */

// 数据库配置
define('DB_HOST', '1Panel-mysql-zFnx');  
define('DB_USER', 'root');              
define('DB_PASS', 'ehk543ijg');       
define('DB_NAME', 'app_store');        

// 站点基本设置
define('SITE_NAME', '应用商店');    // 页面标题
//define('SITE_URL', 'http://localhost/'); // AI生成的好像没什么用 

/**
 * 初始化数据库连接
 */
$maxRetries = 2; // 最大重试次数
$retryDelay = 2; // 秒

for ($i = 0; $i < $maxRetries; $i++) {
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        error_log("[".date('Y-m-d H:i:s')."] 数据库连接失败: ".$conn->connect_error);
        
        if ($i < $maxRetries - 1) {
            sleep($retryDelay);
            continue;
        }
        
        // 最后一次尝试也失败了
        die("<h2>系统维护中</h2>
            <p>应用商店暂时无法访问，技术团队正在处理</p>");
    }
    
    break;
}


?>