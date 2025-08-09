<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'config.php'; // 主配置文件
require_once 'includes/functions.php';

// 获取分类ID
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 获取分类信息
$category = null;
if ($category_id > 0) {
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
}

// 获取该分类下的应用
$apps = [];
if ($category_id > 0) {
    $sql = "SELECT * FROM apps WHERE category_id = ? ORDER BY download_count DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $apps = $result->fetch_all(MYSQLI_ASSOC);
}

// 获取所有分类
$categories = getAllCategories();

// 包含头部
require_once 'includes/header.php';
?>

<div class="container mt-4">
    <h2><?php echo $category ? $category['name'] : '所有分类'; ?></h2>
    
    <div class="row">
        <?php if (empty($apps)): ?>
            <div class="col-12">
                <div class="alert alert-info">该分类下暂无应用</div>
            </div>
        <?php else: ?>
            <?php foreach ($apps as $app): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $app['icon_path']; ?>" class="card-img-top" alt="<?php echo $app['name']; ?>" style="height: 200px; object-fit: contain;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $app['name']; ?></h5>
                            <p class="card-text"><?php echo substr($app['description'], 0, 100); ?>...</p>
                            <a href="details.php?id=<?php echo $app['id']; ?>" class="btn btn-primary">查看详情</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
// 包含底部
require_once 'includes/footer.php';
?>