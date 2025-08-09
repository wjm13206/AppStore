<?php
/*
 * 应用商店配置文件
 * 最后更新：2025-08-08 
 */

// 添加应用页面
session_start();
require_once '../config.php';

// 检查管理员是否登录
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
    $file_path = $conn->real_escape_string($_POST['download_url']); // 将download_url改为file_path以匹配数据库结构
    $icon_path = $conn->real_escape_string($_POST['image']); // 将image改为icon_path以匹配数据库结构
    $screenshots = implode(',', array_map(function($s) use ($conn) {
        return $conn->real_escape_string(trim($s));
    }, explode('\n', $_POST['screenshots'])));
    
    $sql = "INSERT INTO apps (name, category_id, description, download_count, file_path, icon_path, version, size, screenshots) 
            VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // 添加错误检查，确保预处理语句创建成功
    if ($stmt === false) {
        die("预处理语句创建失败: " . $conn->error);
    }
    
    // 注意：由于download_count在SQL中是直接赋值为0，所以不需要在bind_param中绑定该参数
    // SQL语句中有8个占位符，对应8个参数: name, category_id, description, file_path, icon_path, version, size, screenshots
    $stmt->bind_param("sissssss", $name, $category_id, $description, $file_path, $icon_path, $version, $size, $screenshots);
    
    // 添加执行错误检查
    if (!$stmt->execute()) {
        die("执行失败: " . $stmt->error);
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
    <title>外服应用商店 - 添加应用</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
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
    <div class="d-flex">
        <!-- 侧边栏 -->

        <?php include 'sidebar.php'; ?>

        <!-- 主内容区 -->
        <div class="main-content">
            <h2>添加应用</h2>
            <hr>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">应用名称</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">分类</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">选择分类</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="version" class="form-label">版本号</label>
                        <input type="text" class="form-control" id="version" name="version" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="size" class="form-label">应用大小</label>
                        <input type="text" class="form-control" id="size" name="size" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">应用描述</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="download_url" class="form-label">下载链接</label>
                    <input type="url" class="form-control" id="download_url" name="download_url" required>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">应用图标URL</label>
                    <input type="url" class="form-control" id="image" name="image" required>
                </div>
                
                <div class="mb-3">
                    <label for="screenshots" class="form-label">应用截图URL (每行一个)</label>
                    <textarea class="form-control" id="screenshots" name="screenshots" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">添加应用</button>
                <a href="apps.php" class="btn btn-secondary ms-2">取消</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>