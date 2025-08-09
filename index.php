<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // fack bug

// 应用商店v1.1.0 - 首页模块
require_once 'config.php';  // 主配置文件
require_once 'includes/functions.php'; // 工具函数

// 获取热门应用列表
$popularApps = getPopularApps();
$showEmptyState = false;
$errorMessage = '';

if (empty($popularApps)) {
    $showEmptyState = true;
    // 错误信息
    $errorMessage = "暂无应用数据，请先添加应用。";
    error_log('获取热门应用数据为空 - '.date('Y-m-d H:i:s')); // 日志
}

// 头部模板
include 'includes/header.php';
?>

<div class="container mt-4">
    <!-- 首页标题 -->
    <h1 class="text-center mb-4" style="color: #3a7bd5;"><?php echo SITE_NAME; ?></h1>
<!-- 
I've been running through the jungle
我愿穿荆度棘
I've been running with the wolves
我愿与狼共舞
To get to you to get to you
只为你 只为到达你的身边
I've been down the darkest alleys
我曾深陷暗无天日的穷途末路
Saw the dark side of the moon
也曾四面楚歌 走投无路
To get to you to get to you
只为你 只为到达你的身边
I've looked for love in every stranger
我曾向每个陌路人探寻爱的踪影
Took too much to ease the anger
也曾竭尽全力只为释怀心中怒火
All for you yeah all for you
这一切都只为你 只因为你
I've been running through the jungle
我愿穿荆度棘
I've been crying with the wolves
我曾与狼同哮
To get to you to get to you to get to you
只为你 只为到达你的身边
To get to you
只为你
To get to you -->
    <?php if (!empty($showEmptyState)): ?>
    <!-- 空状态处理 -->
    <div class="alert alert-warning">
        <?php echo $errorMessage; ?> 
        <a href="#" onclick="location.reload()">点击刷新</a>
    </div>
    <?php endif; ?>
    
    <?php if (!$showEmptyState || !empty($popularApps)): ?>
    <div class="row app-list">
        <?php foreach ($popularApps as $index => $appData): ?>
        <div class="col-md-4 mb-4 app-item">
            <div class="card h-100">
                <!-- 应用图片，没图片时用默认图 -->
                <?php $imgUrl = !empty($appData['image']) 
                    ? htmlspecialchars($appData['image'], ENT_QUOTES) 
                    : 'assets/images/default-app.png'; ?>
                <img src="<?= $imgUrl ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($appData['name']) ?>"
                     onerror="this.src='assets/images/default-app.png'">
                
                <div class="card-body">
                    <h5 class="card-title"><?= $appData['name'] ?></h5>
                    <!-- 描述截断（100）-->
                    <p class="card-text">
                        <?= mb_substr($appData['description'], 0, 100, 'UTF-8') ?>
                        <?php if (strlen($appData['description']) > 100): ?>...<?php endif; ?>
                    </p>
                    <a href="details.php?id=<?= $appData['id'] ?>" 
                       class="btn btn-primary btn-sm">
                       查看详情 <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php
// 页脚模板 
include 'includes/footer.php';
?>