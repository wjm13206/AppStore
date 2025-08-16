<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 添加应用页面
session_start();
require_once '../config.php';

// 检查是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 获取所有分类
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $version = $conn->real_escape_string($_POST['version']);
    $size = $conn->real_escape_string($_POST['size']);
    $description = $conn->real_escape_string($_POST['description']);
    $file_path = $conn->real_escape_string($_POST['download_url']); 
    $icon_path = $conn->real_escape_string($_POST['image']); 
    $screenshots = implode(',', array_map(function($s) use ($conn) {
        return $conn->real_escape_string(trim($s));
    }, explode('\n', $_POST['screenshots'])));
    
    $sql = "INSERT INTO apps (name, category_id, description, download_count, file_path, icon_path, version, size, screenshots) 
            VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // 错误检查
    if ($stmt === false) {
        header("Location: ../error.php?msg=" . urlencode("预处理语句创建失败: " . $conn->error));
        exit();
    }
    

    $stmt->bind_param("sissssss", $name, $category_id, $description, $file_path, $icon_path, $version, $size, $screenshots);
    
    // 执行错误检查
    if (!$stmt->execute()) {
        header("Location: ../error.php?msg=" . urlencode("执行失败: " . $stmt->error));
        exit();
    }
    
    header("Location: apps.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 添加应用</title>
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
        <!-- 侧边栏 -->
        <?php include 'sidebar.php'; ?>

        <!-- 主内容区 -->
        <div class="main-content">
            <h2>添加应用</h2>
            <hr>
            
            <form class="layui-form" method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">应用名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入应用名称" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-block">
                        <select name="category_id" required lay-verify="required">
                            <option value="">选择分类</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">版本号</label>
                            <div class="layui-input-block">
                                <input type="text" name="version" required lay-verify="required" placeholder="请输入版本号" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">应用大小</label>
                            <div class="layui-input-block">
                                <input type="text" name="size" required lay-verify="required" placeholder="请输入应用大小" class="layui-input">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">应用描述</label>
                    <div class="layui-input-block">
                        <textarea name="description" required lay-verify="required" placeholder="请输入应用描述" class="layui-textarea"></textarea>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">下载链接</label>
                    <div class="layui-input-block">
                        <input type="url" name="download_url" required lay-verify="url" placeholder="请输入下载链接" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">应用图标URL</label>
                    <div class="layui-input-block">
                        <input type="url" name="image" required lay-verify="url" placeholder="请输入应用图标URL" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">应用截图URL</label>
                    <div class="layui-input-block">
                        <textarea name="screenshots" required lay-verify="required" placeholder="请输入应用截图URL，每行一个" class="layui-textarea"></textarea>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">添加应用</button>
                        <a href="apps.php" class="layui-btn layui-btn-primary">取消</a>
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