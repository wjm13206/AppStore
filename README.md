# 应用商店管理系统

一个基于 PHP 和 MySQL 开发的简单应用商店管理系统，支持应用分类、应用展示、搜索和下载功能。

## 功能特性

- 前台功能：
  - 浏览应用和分类
  - 搜索应用
  - 查看应用详情
  - 下载应用
  - 按分类浏览应用

- 后台管理功能：
  - 管理员登录/登出
  - 应用管理（增删改查）
  - 分类管理（增删改查）
  - 应用截图管理

## 系统要求

- PHP 7.0 或更高版本
- MySQL 5.6 或更高版本
- Web 服务器（Apache/Nginx）

## 安装说明

1. 将所有文件上传到 Web 服务器目录
2. 导入 `mysql.sql` 文件创建数据库表结构
3. 修改 `config.php` 文件中的数据库连接配置
4. 默认管理员账户：
   - 用户名：admin
   - 密码：12345678

## 目录结构

```
├── admin/              # 后台管理目录
│   ├── add_app.php     # 添加应用
│   ├── add_category.php # 添加分类
│   ├── apps.php        # 应用管理
│   ├── categories.php  # 分类管理
│   ├── edit_app.php    # 编辑应用
│   ├── edit_category.php # 编辑分类
│   ├── index.php       # 后台首页
│   ├── login.php       # 管理员登录
│   ├── logout.php      # 管理员登出
│   └── sidebar.php     # 后台侧边栏
├── includes/           # 公共包含文件
│   ├── footer.php
│   ├── functions.php   # 公共函数
│   └── header.php
├── templates/          # 模板文件
│   ├── footer.php
│   └── header.php
├── app.php             # 应用列表页
├── category.php        # 分类页
├── config.php          # 配置文件
├── mysql.sql           # 数据库文件
├── details.php         # 应用详情页
├── header.php
├── index.php           # 首页
└── search.php          # 搜索页
```

## 数据库结构

- `categories` 表：存储应用分类信息
- `apps` 表：存储应用信息
- `admins` 表：存储管理员账户信息

## 安全说明

- 管理员密码使用 bcrypt 算法加密存储
## 使用注意事项

1. 请及时修改默认管理员密码
2. 建议定期备份数据库
3. 上传文件请确保服务器安全性

## 许可证

本项目采用极云科技开源软件协议发布，您可以根据需要自由使用、修改和分发本软件。

Copyright (c) [2025] [JiyunTech]