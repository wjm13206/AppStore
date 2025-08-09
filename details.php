<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 详情页
require_once 'config.php';
require_once 'includes/functions.php';

// 应用ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 获取应用详情
$app = getAppDetails($id);
if (!$app) {
    header("Location: index.php");
    exit;
}

// 头部文件
include 'templates/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <?php if(!empty($app['icon_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $app['icon_path'])): ?>
                <img src="<?php echo $app['icon_path']; ?>" class="img-fluid rounded" alt="<?php echo $app['name']; ?>">
            <?php else: ?>
                <img src="assets/images/default-app.png" class="img-fluid rounded" alt="默认图片">
            <?php endif; ?>
            <div class="mt-3">
                <a href="<?php echo !empty($app['file_path']) ? htmlspecialchars($app['file_path'], ENT_QUOTES) : '#'; ?>" class="btn btn-success btn-block">下载应用</a>
            </div>
        </div>
        <div class="col-md-8">
            <h2><?php echo $app['name']; ?></h2>
            <p class="text-muted">版本: <?php echo $app['version']; ?> | 大小: <?php echo $app['size']; ?> | 下载量: <?php echo $app['download_count']; ?></p>
            <hr>
            <h4>应用描述</h4>
            <p><?php echo nl2br($app['description']); ?></p>
            
            <h4 class="mt-4">应用截图</h4>
            <div class="row">
                <?php $screenshots = isset($app['screenshots']) && !empty($app['screenshots']) ? explode(',', $app['screenshots']) : []; ?>
                <?php foreach ($screenshots as $screenshot): ?>
                <div class="col-md-4 mb-3">
                    <img src="<?php echo trim($screenshot); ?>" class="img-thumbnail" alt="应用截图">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
// 包含页脚文件
include 'templates/footer.php';
?>