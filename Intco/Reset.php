<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <title>重置密码</title>
  <style>
    /* 基础样式 */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }
    /* 容器：水平布局 */
    .container {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }
    /* 左侧 Logo 区域 */
    .brand-section {
      margin-right: 30px;
      text-align: center;
    }
    .brand-section img {
      width: 220px; /* 可根据实际需要修改 */
      display: block;
      margin: 0 auto 10px;
    }
    .brand-section h1 {
      font-size: 24px;
      color: #333;
    }
    /* 右侧表单区 */
    .form-section {
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 300px;
    }
    .form-section h2 {
      margin-bottom: 20px;
      font-size: 20px;
      color: #333;
    }
    .form-section input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    .form-section button {
      width: 100%;
      background-color: #ff3333;
      color: #fff;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }
    .form-section button:hover {
      background-color: #e62e2e;
    }
    .back-link {
      display: block;
      margin-top: 15px;
      text-align: center;
      text-decoration: none;
      color: #333;
      font-size: 14px;
    }
    .back-link:hover {
      text-decoration: underline;
    }

    
  </style>
</head>
<body>
  <div class="container">
    <!-- 左侧 Logo / 品牌区 --> 
    <div class="brand-section">
      <!-- 这里可以用实际 Logo 图片替换 logo.png -->
      <img src="picture/logo 1.png" alt="t-int logo"  height="130" >
      
    </div>

    <!-- 右侧表单区 -->
    <div class="form-section">
      <!-- 也可以根据需要添加“重置密码”等标题 -->
      <input type="email" placeholder="School or work e-mail" />
      <button>Send Password Reset Link</button>
      <a href="Login1.php" class="back-link">Back to Login</a>
    </div>
  </div>
</body>
</html>