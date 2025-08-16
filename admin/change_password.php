<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 修改密码页面
session_start();
require_once '../config.php';
require_once '../includes/functions.php';

// 是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 验证输入
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error = "所有字段都是必填的";
    } elseif ($new_password !== $confirm_password) {
        $error = "新密码和确认密码不匹配";
    } elseif (strlen($new_password) < 6) {
        $error = "新密码长度至少为6位";
    } else {
        // 验证旧密码
        $username = $_SESSION['admin_username'];
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($old_password, $user['password'])) {
            // 更新密码
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE admins SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashed_password, $username);
            
            if ($update_stmt->execute()) {
                $success = "密码修改成功";
            } else {
                $error = "密码修改失败，请稍后重试";
            }
        } else {
            $error = "旧密码不正确";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 修改密码</title>
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
            <h2>修改密码</h2>
            <hr>
            
            <?php if (isset($error)): ?>
                <div class="layui-alert layui-alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="layui-alert layui-alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form" method="POST">
                        <div class="layui-form-item">
                            <label class="layui-form-label">旧密码</label>
                            <div class="layui-input-block" style="max-width: 400px;">
                                <input type="password" name="old_password" required lay-verify="required" placeholder="请输入旧密码" class="layui-input">
                            </div>
                        </div>
                        
                        <div class="layui-form-item">
                            <label class="layui-form-label">新密码</label>
                            <div class="layui-input-block" style="max-width: 400px;">
                                <input type="password" name="new_password" required lay-verify="required" placeholder="请输入新密码" class="layui-input">
                                <div class="layui-form-mid layui-word-aux">密码长度至少6位</div>
                            </div>
                        </div>
                        
                        <div class="layui-form-item">
                            <label class="layui-form-label">确认密码</label>
                            <div class="layui-input-block" style="max-width: 400px;">
                                <input type="password" name="confirm_password" required lay-verify="required" placeholder="请再次输入新密码" class="layui-input">
                            </div>
                        </div>
                        
                        <div class="layui-form-item">
                            <div class="layui-input-block" style="max-width: 400px;">
                                <button class="layui-btn" lay-submit lay-filter="formDemo">修改密码</button>
                                <a href="index.php" class="layui-btn layui-btn-primary">返回首页</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="//unpkg.com/layui@2.11.5/dist/layui.js"></script>
    <script>
    layui.use('form', function(){
      var form = layui.form;
    });
    </script>
</body>
</html>