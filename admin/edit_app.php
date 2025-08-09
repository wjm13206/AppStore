<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 编辑应用页面
session_start();
require_once '../config.php';

// 检查是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 获取应用ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 获取所有分类
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// 获取应用详情
$app = $conn->query("SELECT * FROM apps WHERE id = $id")->fetch_assoc();
if (!$app) {
    header("Location: apps.php");
    exit;
}

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
    
    $sql = "UPDATE apps SET name = ?, category_id = ?, version = ?, size = ?, description = ?, file_path = ?, icon_path = ?, screenshots = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // 错误检查
    if ($stmt === false) {
        die("预处理语句创建失败: " . $conn->error);
    }
    
    $stmt->bind_param("sissssssi", $name, $category_id, $version, $size, $description, $file_path, $icon_path, $screenshots, $id);
    $stmt->execute();
    
    header("Location: apps.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 编辑应用</title>
    <link rel="stylesheet" href="//unpkg.com/layui@2.11.5/dist/css/layui.css">
    <style>
        .layui-layout-admin .layui-side {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 56px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #f8f9fa;
        }
        
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content container-fluid py-4" style="margin-left: 220px;">
        <div class="container mt-4">
            <h2 class="mb-4">编辑应用</h2>
            
            <form method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">应用名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($app['name']); ?>" required lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-block">
                        <select name="category_id" required lay-verify="required">
                            <option value="">请选择分类</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $app['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">版本</label>
                    <div class="layui-input-block">
                        <input type="text" name="version" value="<?php echo htmlspecialchars($app['version']); ?>" required lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">大小</label>
                    <div class="layui-input-block">
                        <input type="text" name="size" value="<?php echo htmlspecialchars($app['size']); ?>" required lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">描述</label>
                    <div class="layui-input-block">
                        <textarea name="description" required lay-verify="required" class="layui-textarea"><?php echo htmlspecialchars($app['description']); ?></textarea>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">下载链接</label>
                    <div class="layui-input-block">
                        <input type="text" name="download_url" value="<?php echo htmlspecialchars($app['file_path']); ?>" required lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">图标链接</label>
                    <div class="layui-input-block">
                        <input type="text" name="image" value="<?php echo htmlspecialchars($app['icon_path']); ?>" required lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">截图链接</label>
                    <div class="layui-input-block">
                        <textarea name="screenshots" required lay-verify="required" class="layui-textarea"><?php echo str_replace(',', "\n", htmlspecialchars($app['screenshots'] ?? '')); ?></textarea>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">保存更改</button>
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