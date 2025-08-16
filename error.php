<?php
// 定义SITE_NAME常量（如果尚未定义）
if (!defined('SITE_NAME')) {
    define('SITE_NAME', '应用商店管理系统');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>错误 - <?php echo SITE_NAME; ?></title>
    <link href="//unpkg.com/layui@2.11.5/dist/css/layui.css" rel="stylesheet">
    <style>
        body {
            font-family: "Helvetica Neue", sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .error-title {
            color: #ff4d4f;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .error-message {
            color: #595959;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #009688;
            color: #ffffff;
            border-radius: 4px;
            text-decoration: none;
        }
        .back-link:hover {
            background-color: #007a66;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-title">发生错误</div>
        <div class="error-message">
            <?php
            // 由调用页面传递
            if (isset($_GET['msg'])) {
                echo htmlspecialchars($_GET['msg']);
            } else {
                echo "发生未知错误，请返回重试。";
            }
            ?>
        </div>
        <a href="javascript:history.back()" class="back-link">返回上一页</a>
    </div>
</body>
</html>