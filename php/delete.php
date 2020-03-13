<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'sarugan2_board');
define('DB_PASS', 'root');
define('DB_NAME', 'sarugan2_board');

date_default_timezone_set('Asia/Tokyo');

$message_id = null;
$mysqli = null;
$sql = null;
$res = null;
$error_message = array();
$message_data = array();

session_start();

if(empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true ) {
    header("Location: ./admin.php");
}

if(!empty($_GET['message_id']) && empty($_POST['message_id'])) {
    $message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_errno) {
        $error_message[] = 'データベースの接続に失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
    } else {
        $sql = "SELECT * FROM message WHERE id = $message_id";
        $res = $mysqli->query($sql);

        if($res) {
            $message_data = $res->fetch_assoc();
        } else {
            header("Location: ./admin.php");
        }
        $mysqli->close();
    }
} elseif(!empty($_POST['message_id'])) {
    $message_id = (int)htmlspecialchars($_POST['message_id'], ENT_QUOTES);

        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if($mysqli->connect_errno) {
            $error_message[] = 'データベースの接続に失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
        } else {
            $sql = "DELETE FROM message WHERE id = $message_id";
            $res = $mysqli->query($sql);
        }

        $mysqli->close();

        if($res) {
            header("Location: ./admin.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/mainstyle.css?v=2">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>削除</title>
</head>
<body>
    <?php if(!empty($error_message)): ?>
        <ul class="error_message">
            <?php foreach($error_message as $value): ?>
             <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form action="" method="post">
    <div>
    <h1><a href="../index.html">Words to Works</a></h1>
        <label for="view_name">書籍・ドラマ名</label>
        <input type="text" id="view_name" name="view_name" value="<?php if(!empty($message_data['view_name'])) {echo $message_data['view_name'];} ?> "disabled>
    </div>
    <div>
        <label for="message">言葉</label>
        <textarea name="message" id="message" disabled><?php if(!empty($message_data['message'])) {echo $message_data['message'];} ?></textarea>
    </div>
    <div id="sent">
        <a href="admin.php" class="btn_cancel">キャンセル</a>
    <input id="write" type="submit" name="btn_submit" value="削除">
    <input type="hidden" name="message_id" value="<?php echo $message_data['id']; ?>">
    </div>
    </form>
</body>
</html>