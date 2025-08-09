<?php
// 调试模式
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 详情页
require_once 'config/database.php';
require_once 'includes/header.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 获取详情
    $sql = "SELECT * FROM apps WHERE id = $id";
    $result = $conn->query($sql);
    
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <img src="<?php echo $row['image_url']; ?>" class="img-fluid" alt="<?php echo $row['name']; ?>">
                </div>
                <div class="col-md-8">
                    <h1><?php echo $row['name']; ?></h1>
                    <p class="text-muted">版本: <?php echo $row['version']; ?> | 大小: <?php echo $row['size']; ?>MB</p>
                    <p><?php echo $row['description']; ?></p>
                    <a href="<?php echo $row['download_url']; ?>" class="btn btn-success btn-lg">下载</a>
                </div>
            </div>
            
            <!-- 截图 -->
            <div class="row mt-4">
                <div class="col-12">
                    <h3>应用截图</h3>
                    <div class="row">
                        <?php 
                        $screenshots = explode(',', $row['screenshots']);
                        foreach($screenshots as $screenshot) {
                            echo '<div class="col-md-3 mb-3">';
                            echo '<img src="'.$screenshot.'" class="img-thumbnail" alt="截图">';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger">应用不存在</div>
        <?php
    }
} else {
    ?>
    <div class="alert alert-warning">请选择应用</div>
    <?php
}

require_once 'includes/footer.php';
?>