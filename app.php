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
    
    
    //果然不能乱用AI不过能用就行
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        echo '<div class="container mt-4">';
        echo '<div class="row">';
        echo '<div class="col-md-4">';
        echo '<img src="'.$row['image_url'].'" class="img-fluid" alt="'.$row['name'].'">';
        echo '</div>';
        echo '<div class="col-md-8">';
        echo '<h1>'.$row['name'].'</h1>';
        echo '<p class="text-muted">版本: '.$row['version'].' | 大小: '.$row['size'].'MB</p>';
        echo '<p>'.$row['description'].'</p>';
        echo '<a href="'.$row['download_url'].'" class="btn btn-success btn-lg">下载</a>';
        echo '</div></div>';
        
        // 截图
        echo '<div class="row mt-4">';
        echo '<div class="col-12">';
        echo '<h3>应用截图</h3>';
        echo '<div class="row">';
        
        $screenshots = explode(',', $row['screenshots']);
        foreach($screenshots as $screenshot) {
            echo '<div class="col-md-3 mb-3">';
            echo '<img src="'.$screenshot.'" class="img-thumbnail" alt="截图">';
            echo '</div>';
        }
        
        echo '</div></div></div>';
    } else {
        echo '<div class="alert alert-danger">应用不存在</div>';
    }
} else {
    echo '<div class="alert alert-warning">请选择应用</div>';
}

require_once 'includes/footer.php';
?>