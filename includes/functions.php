<?php
// 功能函数

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

/**
 * 搜索应用函数
 * 
 * @param string $query 搜索关键词
 * @return array 搜索结果数组
 */
function searchApps($query) {
    global $conn;
    
    if (empty($query)) {
        return [];
    }
    
    $searchQuery = "%" . $conn->real_escape_string($query) . "%";
    $sql = "SELECT apps.*, categories.name AS category_name 
            FROM apps 
            LEFT JOIN categories ON apps.category_id = categories.id 
            WHERE apps.name LIKE ? OR apps.description LIKE ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("SQL语句准备失败: " . $conn->error);
        return [];
    }
    
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if (!$result) {
        error_log("查询执行失败: " . $stmt->error);
        return [];
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * 获取应用总数
 * 
 * @return int 应用总数
 */
function getTotalApps() {
    global $conn;
    
    $result = $conn->query("SELECT COUNT(*) FROM apps");
    if ($result) {
        return $result->fetch_row()[0];
    }
    return 0;
}

/**
 * 获取分类总数
 * 
 * @return int 分类总数
 */
function getTotalCategories() {
    global $conn;
    
    $result = $conn->query("SELECT COUNT(*) FROM categories");
    if ($result) {
        return $result->fetch_row()[0];
    }
    return 0;
}

/**
 * 获取总下载量
 * 
 * @return int 总下载量
 */
function getTotalDownloads() {
    global $conn;
    
    $result = $conn->query("SELECT SUM(download_count) FROM apps");
    if ($result) {
        return $result->fetch_row()[0] ?? 0;
    }
    return 0;
}

/**
 * 检查是否已登录
 * 
 * @return bool 如果已登录返回true，否则返回false
 */
function isAdminLoggedIn() {
    // 检查session中的admin_logged_in变量
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
?>