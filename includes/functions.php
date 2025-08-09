<?php
// 功能函数 Ai x3

function getPopularApps($limit = 6) {
    global $conn;
    
    // 检查数据库连接
    if (!$conn) {
        error_log("数据库连接不存在");
        return [];
    }
    
    $sql = "SELECT * FROM apps ORDER BY download_count DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    
    // 检查prepare是否成功
    if (!$stmt) {
        error_log("SQL语句准备失败: " . $conn->error);
        return [];
    }
    
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    // 检查查询结果
    if (!$result) {
        error_log("查询执行失败: " . $stmt->error);
        return [];
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAppDetails($id) {
    global $conn;
    
    $sql = "SELECT * FROM apps WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAllCategories() {
    global $conn;
    
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);
    
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>