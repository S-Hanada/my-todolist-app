<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
//AuthControllersクラスをインスタンス
$auth_controllers = new AuthController();
if($_POST['user']) {
	//DB接続
	$auth_controllers->sendMail();
}
//DB登録のエラーメッセージを取得
session_start();
if($_SESSION['errors']) {
	$errors = $_SESSION['errors'];
	$_SESSION = array();
}
var_dump($errors);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ユーザー新規登録</title>
</head>
<body>
<h1>ユーザー新規登録</h1>
<p>登録するメールアドレスを入力してください</p>
<p><?php echo $errors; ?></p>
<form action="sendmail.php" method="post" id="sendmail">
	<dl>
		<dt><label for="email">E-MAIL</label></dt>
		<dd><input type="email" name="email"></dd>
	</dl>
	<input type="submit" value="送信">
</form>
<br>
<a href="login.php">ログイン画面に戻る</a>