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
    <title><?php echo SITE_NAME; ?> - 应用管理</title>
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
            <h2>应用管理</h2>
            <hr>
            
            <div class="layui-btn-container">
                <a href="add_app.php" class="layui-btn">添加应用</a>
            </div>
            
            <table class="layui-table" lay-even>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>应用名称</th>
                        <th>分类</th>
                        <th>版本</th>
                        <th>大小</th>
                        <th>下载量</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($apps as $app): ?>
                    <tr>
                        <td><?php echo $app['id']; ?></td>
                        <td><?php echo htmlspecialchars($app['name']); ?></td>
                        <td><?php echo htmlspecialchars($app['category_name'] ?? '未分类'); ?></td>
                        <td><?php echo htmlspecialchars($app['version']); ?></td>
                        <td><?php echo htmlspecialchars($app['size']); ?></td>
                        <td><?php echo $app['download_count']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($app['created_at'])); ?></td>
                        <td>
                            <div class="layui-btn-group">
                                <a href="edit_app.php?id=<?php echo $app['id']; ?>" class="layui-btn layui-btn-sm">编辑</a>
                                <a href="apps.php?delete=<?php echo $app['id']; ?>" class="layui-btn layui-btn-danger layui-btn-sm" onclick="return confirm('确定要删除这个应用吗？')">删除</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="//unpkg.com/layui@2.11.5/dist/layui.js"></script>
</body>
</html>