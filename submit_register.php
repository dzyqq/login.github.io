<?php
// 数据库连接
$host = 'localhost';
$dbname = 'dzy2008';
$username = 'dzyroot';
$password = '200818';

// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取用户输入的注册数据
$user_name = $_POST['username'] ?? '';
$user_email = $_POST['email'] ?? '';
$user_password = $_POST['password'] ?? '';

// 输入验证
if (empty($user_name) || empty($user_email) || empty($user_password)) {
    header("Location: register.html?error=请输入所有字段");
    exit;
}

// 密码加密
$hashed_password = md5($user_password); // 这里可以使用更强的加密方法，比如 password_hash()

// 检查用户是否已经存在
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: register.html?error=该邮箱已被注册");
    exit;
}

// 插入用户数据到数据库
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user_name, $user_email, $hashed_password);

if ($stmt->execute()) {
    // 注册成功，重定向到登录页面
    header("Location: login.html?message=注册成功，请登录");
    exit;
} else {
    // 如果注册失败，显示错误信息
    header("Location: register.html?error=注册失败，请稍后再试");
    exit;
}

// 关闭连接
$stmt->close();
$conn->close();
?>
