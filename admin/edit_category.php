<?php
// 编辑分类页面
session_start();
require_once '../config.php';

// 检查是否登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 获取分类ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 获取分类详情
$category = $conn->query("SELECT * FROM categories WHERE id = $id")->fetch_assoc();
if (!$category) {
    header("Location: categories.php");
    exit;
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    
    $sql = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    
    header("Location: categories.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>外服应用商店 - 编辑分类</title>
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
            <h2>编辑分类</h2>
            <hr>
            
            <form class="layui-form" method="POST">
                <div class="layui-form-item">
                    <label class="layui-form-label">分类名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">保存更改</button>
                        <a href="categories.php" class="layui-btn layui-btn-primary">取消</a>
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