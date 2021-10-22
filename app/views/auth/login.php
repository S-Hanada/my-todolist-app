<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
//AuthControllersクラスをインスタンス
$auth_controllers = new AuthController();
if($_POST['user']) {
	//DB接続
	$auth_controllers->login();
}
session_start();
//DB登録のエラーメッセージを取得
if($_SESSION['errors']) {
	$errors = $_SESSION['errors'];
	$_SESSION = array();
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TODOリスト</title>
</head>
<body>
<h1>TODOリスト</h1>
<h2>ログインする</h2>
<p><?php echo $errors; ?></p>
<form action="login.php" method="post" id="login">
	<dl>
		<dt><label for="user">ID</label></dt>
		<dd><input type="text" name="user" value="<?php echo $_GET["user_id"]; ?>"></dd>
		<dt><label for="password">PASSWORD</label></dt>
		<dd><input type="password" name="password"></dd>
	</dl>
	<input type="submit" value="送信">
</form>
