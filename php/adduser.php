<?php

ini_set('display/errors', true);
error_reporting(E_ALL);

session_start();

require 'database.php';

$err = [];

if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
  $user_name = filter_input(INPUT_POST, 'user_name');
  $password = filter_input(INPUT_POST, 'password');
  $password_conf = filter_input(INPUT_POST, 'password_conf');

  if($user_name === '') {
    $err['user_name'] = 'ユーザー名は入力必須です。';
  }
  if($password === '') {
    $err['password'] = 'パスワードは入力必須です。';
  }
  if($password !== $password_conf) {
    $err['password_conf'] = 'パスワードが一致しません。';
  }

  if (count($err) === 0) {
    $pdo = connect();
    
    $stmt = $pdo->prepare('INSERT INTO `User` (`id`, `user_name`, `password`) VALUES (null, ?, ?)');

    $params = [];
    $params[] = $user_name;
    $params[] = password_hash($password, PASSWORD_DEFAULT);

    $success = $stmt->execute($params);
  }

}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/phpstyle.css?v=2">
    <link href="https://fonts.googleapis.com/css?family=Spartan&display=swap" rel="stylesheet">
  <title></title>
</head>
<body>
  <div id="wrapper">

  <?php if (isset($success) && $success) : ?>
    <p>Thank You</p>
    <p><a href="login_form.php">こちらからログインしてください。</a></p>
  <?php else: ?>
    <div id=form2>
      <h1>WELCOME</h1>
    <form action="" method="post">
      <p>
        <!-- ユーザー名 -->
        <input type="text" id="user_id" name="user_name" placeholder="new user name">
      </p>
      <p>
        <!-- パスワード -->
        <input type="password" id="password" name="password" placeholder="new password">
      </p>
      <p>
        <!-- 確認用 -->
        <input type="password" name="password_conf" id="password_conf" placeholder="again new password">
      </p>
        <?php if(count($err) > 0) : ?>
    <?php foreach ($err as $e) : ?>
    <p class="error"><?php echo h($e); ?></p>
    <?php endforeach; ?>
  <?php endif; ?>
      <p>
        <button type="submit">SIGN UP</button>
        <a href="login_form.php" id="new2">すでに登録されている方はこちら</a>
        <a href="../index.html" id="new2">ホームへ</a>
      </p>
    </form>
    </div>
    </div>
  <?php endif; ?>
</body>
</html>