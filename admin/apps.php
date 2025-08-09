<?php
// 应用管理页面
session_start();
require_once '../config.php';

// 检查管理员是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 获取所有应用
$apps = $conn->query("SELECT apps.*, categories.name AS category_name FROM apps LEFT JOIN categories ON apps.category_id = categories.id")->fetch_all(MYSQLI_ASSOC);

// 处理删除请求
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM apps WHERE id = $id");
    header("Location: apps.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>外服应用商店 - 应用管理</title>
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
        <?php include 'sidebar.php'; ?> 

        <!-- 主内容区 -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>应用管理</h2>
                <a href="add_app.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>添加应用
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>分类</th>
                            <th>版本</th>
                            <th>下载量</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apps as $app): ?>
                        <tr>
                            <td><?php echo $app['id']; ?></td>
                            <td><?php echo $app['name']; ?></td>
                            <td><?php echo $app['category_name']; ?></td>
                            <td><?php echo $app['version']; ?></td>
                            <td><?php echo $app['download_count']; ?></td>
                            <td>
                                <a href="edit_app.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="apps.php?delete=<?php echo $app['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('确定删除此应用吗？')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>