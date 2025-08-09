<?php
// 搜索页面
require_once 'config.php';
require_once 'includes/functions.php';

// 获取关键词
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// 执行搜索查询 （ai X2）
if (!empty($query)) {
    $searchQuery = "%" . $conn->real_escape_string($query) . "%";
    $sql = "SELECT apps.*, categories.name AS category_name 
            FROM apps 
            LEFT JOIN categories ON apps.category_id = categories.id 
            WHERE apps.name LIKE ? OR apps.description LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 页面头部
require_once 'includes/header.php';
?>

<div class="container mt-4">
    <h2>搜索结果</h2>
    
    <?php if (empty($query)): ?>
        <div class="alert alert-info">请输入搜索</div>
    <?php elseif (empty($results)): ?>
        <div class="alert alert-warning">没有找到匹配的应用</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($results as $app): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($app['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($app['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($app['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($app['description']); ?></p>
                            <a href="details.php?id=<?php echo $app['id']; ?>" class="btn btn-primary">查看详情</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
// 页面底部
require_once 'includes/footer.php';
?>