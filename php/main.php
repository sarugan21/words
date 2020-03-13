<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'sarugan2_board');
define('DB_PASS', 'root');
define('DB_NAME', 'sarugan2_board');

date_default_timezone_set('Asia/Tokyo');

$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();

session_start();

if(!empty($_POST['btn_submit'])) {

    if(empty($_POST['view_name'])) {
        $error_message[] = '書籍・ドラマ・映画名を入力してください。';
    } else {
        $clean['view_name'] = htmlspecialchars($_POST['view_name'], ENT_QUOTES);
        $clean['view_name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['view_name']);
    }

    if(empty($_POST['message'])) {
        $error_message[] = '言葉を入力してください。';
    } else {
        $clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
    }

    if(empty($error_message)) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_errno) {
        $error_message[] = '書き込みに失敗しました。エラー番号'.$mysqli->connect_errno.' : '/$mysqli->connect_error;
    } else {
        $mysqli->set_charset('utf8');

        $now_date = date("Y-m-d H:i:s");

        $sql = "INSERT INTO message(view_name, message, post_date) VALUES ('$clean[view_name]', '$clean[message]', '$now_date')";

        $res = $mysqli->query($sql);

        if($res) {
            $success_message = 'メッセージを書き込みました。';
        } else {
            $error_message[] = '書き込みに失敗しました。';
        }

        $mysqli->close();
    }
  }
}


$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if($mysqli->connect_errno) {
    $error_message[] = 'データの読み込みに失敗しました。エラー番号'.$mysqli->connect_errno.' : '.$mysqli->connect_error;
} else {
    $sql = "SELECT view_name,message,post_date FROM message ORDER BY post_date DESC";
    $res = $mysqli->query($sql);

    if($res) {
        $message_array = $res->fetch_all(MYSQLI_ASSOC);
    }
    $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/mainstyle.css?v=2">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>掲示板</title>
</head>
<body>
    <h1><a href="../index.html">Words to Works</a></h1>
    <?php if(!empty($success_message)): ?>
        <p class="success_message"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if(!empty($error_message)): ?>
        <ul class="error_message">
            <?php foreach($error_message as $value): ?>
             <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form action="" method="post">
    <div>
        <label for="view_name">書籍・ドラマ・映画名</label>
        <input type="text" id="view_name" name="view_name" placeholder="">
    </div>
    <div>
        <label for="message">言葉</label>
        <textarea name="message" id="message" cols="10" rows="10"></textarea>
    </div>
    <div id="sent">
    <input id="write" type="submit" name="btn_submit" value="書き込む">
    </div>
    </form>
    <hr>
    <section>
        <?php if(!empty($message_array)): ?>
            <?php foreach($message_array as $value): ?>
                <article>
                    <div class="info">
                        <h2><?php echo $value['view_name']; ?></h2>
                        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
                    </div>
                    <p><?php echo nl2br($value['message']); ?></p>
                </article>
            <?php endforeach; ?>
            <?php endif; ?>
    </section>
</body>
</html>