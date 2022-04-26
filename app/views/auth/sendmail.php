<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
//AuthControllersクラスをインスタンス
$auth_controllers = new AuthController();
if($_POST) {
	$auth_controllers->sendMail();
}
//DB登録のエラーメッセージを取得
session_start();
if($_SESSION['success']) {
	$success = $_SESSION['success'];
	$_SESSION = array();
}
if($_SESSION['errors']) {
	$errors = $_SESSION['errors'];
	$_SESSION = array();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ユーザー新規登録</title>
</head>
<body>
<?php if($success) : ?>
	<h1><?php echo $success; ?><h1>
	<p>確認用のメールを送りしました。メールに記載されたURLにアクセスし、本登録行ってください</p>
<?php else : ?>
	<h1><?php echo $errors; ?></h1>
	<p>メールの送信に失敗しました。サイト管理者にお問い合わせください</p>
<?php endif; ?>
<br>
<a href="login.php">ログイン画面に戻る</a>