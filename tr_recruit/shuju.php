<?php
header('content-type:text/html;charset=utf-8');
//注册页面
//连接数据库
$con=mysqli_connect("localhost","root","root","shuju");
if (mysqli_connect_errno($con))
{
    echo "连接 MySQL 失败: " . mysqli_connect_error();
}


// 数据库配置
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "shuju";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取表单数据
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 使用 htmlspecialchars 处理输入数据，防止 XSS 攻击
    $name = htmlspecialchars($_POST["name"]);
    $id_card = htmlspecialchars($_POST["id_card"]);
    $gender = isset($_POST["gender"]) ? htmlspecialchars($_POST["gender"]) : '';
    $city = htmlspecialchars($_POST["city"]);
    $school = htmlspecialchars($_POST["school"]);
    $contact = htmlspecialchars($_POST["contact"]);
    $agreement = isset($_POST["agreement"]) ? 1 : 0; // 检查协议是否同意

    // 处理上传的文件
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES["photo"]["tmp_name"];
        $file_name = $_FILES["photo"]["name"];
        $upload_dir = "uploads/"; // 确保这个目录存在并具有写入权限
        $upload_file = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $upload_file)) {
            // 上传成功
        } else {
            echo "文件上传失败";
            $upload_file = NULL; // 上传失败时设置为 NULL
        }
    } else {
        $upload_file = NULL; // 如果没有上传文件
    }

    // SQL 插入语句
    $sql = "INSERT INTO information (姓名, 身份证, 性别, 居住城市, 毕业院校, 联系方式, 照片, 协议) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // 准备并绑定
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssssi", $name, $id_card, $gender, $city, $school, $contact, $upload_file, $agreement);

        // 执行语句
        if ($stmt->execute()) {
            echo "信息已成功保存到数据库中";
        } else {
            echo "执行错误: " . $stmt->error;
        }

        // 关闭语句
        $stmt->close();
    } else {
        echo "准备语句错误: " . $conn->error;
    }
}

// 关闭连接
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .aaa {
            width: 100px;
            height: 100px;
        
        }
    </style>
</head>
<body>
    <a href="index.html">返回主页</a>
</body>
</html>
