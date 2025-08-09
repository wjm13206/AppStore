<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <!-- 
        应用商店 - 主模板
        最后更新：2025-7-15 
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - 发现好用的应用</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap图标库 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- 自定义样式 -->
    <style>
        /* 全局样式调整 */
        body {
            padding-top: 56px;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif;
        }
        
        /* 导航栏样式  */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #3a7bd5 !important; 
        }
        
        /* 卡片悬停效果 */
        .card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
        }
        
        .search-box {
            min-width: 250px;
        }
        
        /* 响应式调整 - 手机端导航栏 */
        @media (max-width: 768px) {
            .navbar-collapse {
                padding-top: 15px;
            }
            .search-box {
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
    
    <!-- 头部统计代码 -->
    <!-- Google Analytics -->
    <!-- <script async src="https://123.com"></script> -->
    </script>
</head>
<body>
    <!-- 导航栏开始 -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <!-- LOGO -->
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-box-seam me-2"></i><?php echo SITE_NAME; ?>
            </a>
            
            <!-- 移动端菜单按钮 -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="切换导航">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- 导航菜单 -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-house-door me-1"></i>首页
                        </a>
                    </li>
                    
                    <!-- 分类下拉菜单 -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid me-1"></i>分类
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            <?php 
                            // 分类数据
                            $categories = getAllCategories();
                            if (!empty($categories)): 
                                foreach ($categories as $category): 
                            ?>
                            <li>
                                <a class="dropdown-item" href="category.php?id=<?= htmlspecialchars($category['id']) ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                    <?php if($category['new']): ?>
                                        <span class="badge bg-danger ms-2">New</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                            <li><a class="dropdown-item text-muted" href="#">暂无分类</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="categories.php"><i class="bi bi-list-ul me-1"></i>全部分类</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="toplist.php">
                            <i class="bi bi-trophy me-1"></i>排行榜
                        </a>
                    </li>
                </ul>
                
                <!-- 搜索框 -->
                <form class="d-flex search-box" action="search.php" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="搜索应用..." aria-label="搜索" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- 右侧导航项 -->
                <ul class="navbar-nav ms-3">
                    <?php if (isAdminLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-shield-lock me-1"></i>管理
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="admin/dashboard.php"><i class="bi bi-speedometer2 me-1"></i>控制台</a></li>
                            <li><a class="dropdown-item" href="admin/apps.php"><i class="bi bi-collection me-1"></i>应用管理</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="admin/logout.php"><i class="bi bi-box-arrow-right me-1"></i>退出</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">
                            <i class="bi bi-person-circle me-1"></i>登录
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- 导航栏结束 -->

</body>
</html>