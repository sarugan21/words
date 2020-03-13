<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

session_start();

require 'database.php';

$err = [];

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
  $user_name = filter_input(INPUT_POST, 'user_name');
  $password = filter_input(INPUT_POST, 'password');

  if($user_name === '') {
    $err['user_name'] = 'ユーザー名は入力必須です。';
  }
  if($password === '') {
    $err['password'] = 'パスワードは入力必須です。';
  }

  if(count($err) === 0) {
    $pdo = connect();

    $stmt = $pdo->prepare('SELECT * FROM User WHERE user_name = ?');

    $params = [];
    $params[] = $user_name;

    $stmt->execute($params);

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
      $password_hash = $row['password'];

      if (password_verify($password, $password_hash)) {
        session_regenerate_id(true);
        $_SESSION['login_user'] = $row;
        header('Location:main.php');
        return;
      }
    }
    $err['login'] = 'ログインに失敗しました。';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="../css/phpstyle.css?v=2">
  <link href="https://fonts.googleapis.com/css?family=Spartan&display=swap" rel="stylesheet">
</head>
<body>
  <div id="wrapper">
    <form action="" method="post">
      <?php if (isset($err['login'])) : ?>
        <p class = "error"><?php echo h($err['login']); ?></p>
      <?php endif; ?>
      <div id="form1">
      <h1>WELCOME<br>BACK</h1>
      <p>
        <!-- ユーザー名 -->
        <input type="text" id="user_id" name="user_name" placeholder="user name">
        <?php if(isset($err['user_name'])) : ?>
          <p class="error"> <?php echo h($err['user_name']); ?></p>
        <?php endif; ?>
      </p>
      <p>
        <!-- パスワード -->
        <input type="password" id="password" name="password" placeholder = "password">
        <?php if (isset($err['password'])) : ?>
          <p class="error"><?php echo h($err['password']); ?> </p>
        
        <?php endif; ?>
      </p>
      </div>
      <p>
        <button type="submit">LOGIN</button>
        <a href="adduser.php" id="new2">登録がまだの方はこちら</a>
        <a href="../index.html" id="new2">ホームへ</a>
      </p>
    </form>
  </div>
</body>
</html>