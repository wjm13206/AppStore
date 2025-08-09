<?php
// 分类管理页面
session_start();
require_once '../config.php';

// 检查管理员是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 获取所有分类
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// 处理删除请求
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: categories.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 分类管理</title>
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
            <h2>分类管理</h2>
            <hr>
            
            <div class="layui-btn-container">
                <a href="add_category.php" class="layui-btn">添加分类</a>
            </div>
            
            <table class="layui-table" lay-even>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>分类名称</th>
                        <th>描述</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($category['created_at'])); ?></td>
                        <td>
                            <div class="layui-btn-group">
                                <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="layui-btn layui-btn-sm">编辑</a>
                                <a href="categories.php?delete=<?php echo $category['id']; ?>" class="layui-btn layui-btn-danger layui-btn-sm" onclick="return confirm('确定要删除这个分类吗？')">删除</a>
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