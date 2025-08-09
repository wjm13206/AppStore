<?php
// 编辑应用页面
session_start();
require_once '../config.php';

// 检查管理员是否登录
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
    $file_path = $conn->real_escape_string($_POST['download_url']); // 将download_url改为file_path以匹配数据库结构
    $icon_path = $conn->real_escape_string($_POST['image']); // 将image改为icon_path以匹配数据库结构
    $screenshots = implode(',', array_map(function($s) use ($conn) {
        return $conn->real_escape_string(trim($s));
    }, explode('\n', $_POST['screenshots'])));
    
    $sql = "UPDATE apps SET name = ?, category_id = ?, version = ?, size = ?, description = ?, file_path = ?, icon_path = ?, screenshots = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // 添加错误检查，确保预处理语句创建成功
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
    <title>外服应用商店 - 编辑应用</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 56px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #f8f9fa;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content container-fluid py-4" style="margin-left: 220px;">
        <div class="container mt-4">
            <h2 class="mb-4">编辑应用</h2>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">应用名称</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($app['name']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">分类</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">请选择分类</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $app['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="version" class="form-label">版本</label>
                    <input type="text" class="form-control" id="version" name="version" value="<?php echo htmlspecialchars($app['version']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="size" class="form-label">大小</label>
                    <input type="text" class="form-control" id="size" name="size" value="<?php echo htmlspecialchars($app['size']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">描述</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($app['description']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="download_url" class="form-label">下载链接</label>
                    <input type="text" class="form-control" id="download_url" name="download_url" value="<?php echo htmlspecialchars($app['file_path']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">图标链接</label>
                    <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($app['icon_path']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="screenshots" class="form-label">截图链接（每行一个）</label>
                    <textarea class="form-control" id="screenshots" name="screenshots" rows="5" required><?php echo str_replace(',', "\n", htmlspecialchars($app['screenshots'] ?? '')); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">保存更改</button>
                <a href="apps.php" class="btn btn-secondary ms-2">取消</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>