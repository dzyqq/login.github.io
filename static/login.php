<?php
session_start();

// 数据库连接信息
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

// 获取表单提交的数据
$user_name = $_POST['username'] ?? '';
$user_password = $_POST['password'] ?? '';

// 表单验证
if (empty($user_name) || empty($user_password)) {
    header("Location: ../login.html?error=请输入用户名和密码");
    exit;
}

// 查询数据库验证用户
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('SQL 错误: ' . $conn->error);
}

$stmt->bind_param("s", $user_name); // 绑定参数
$stmt->execute();
$stmt->store_result();

// 检查用户是否存在
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_username, $db_password); // 绑定结果
    $stmt->fetch();

    // 比较密码
    if (md5($user_password) === $db_password) {
        // 登录成功，存储用户会话
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $db_username;
        header("Location: ./shipin/index.php"); // 重定向到登录后的页面
        exit;
    } else {
        header("Location: ../login.html?error=密码错误");
        exit;
    }
} else {
    header("Location: ../login.html?error=用户不存在");
    exit;
}

// 关闭连接
$stmt->close();
$conn->close();
?>
