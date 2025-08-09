<?php
// 登录页面
session_start();
require_once '../config.php';

// 检查是否已登录
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

// 表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // 验证账号
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "用户名或密码错误";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 管理员登录</title>
    <link rel="stylesheet" href="//unpkg.com/layui@2.11.5/dist/css/layui.css">
    <style>
        body {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5;
        }
        .login-form {
            width: 400px;
            padding: 30px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2 class="layui-form-label" style="text-align: center;">管理员登录</h2>
            <?php if (isset($error)): ?>
                <div class="layui-form-item">
                    <div class="layui-alert layui-alert-danger"><?php echo $error; ?></div>
                </div>
            <?php endif; ?>
            
            <form class="layui-form" method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" required lay-verify="required" placeholder="请输入用户名" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo" style="width: 100%;">登录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="//unpkg.com/layui@2.11.5/dist/layui.js"></script>
    <script>
    layui.use('form', function(){
      var form = layui.form;
      form.render();
    });
    </script>
</body>
</html>