-- 创建数据库
CREATE DATABASE dzyroot;

-- 选择使用该数据库
USE dzyroot;

-- 创建用户表
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- 主键，自增
    username VARCHAR(100) NOT NULL,     -- 用户名
    email VARCHAR(100) NOT NULL UNIQUE, -- 邮箱，唯一
    password VARCHAR(255) NOT NULL,     -- 密码
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- 创建时间
);

