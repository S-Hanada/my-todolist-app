<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
//AuthControllersクラスをインスタンス
$auth_controllers = new AuthController();
if($_POST) {
	//DB接続
	$user = $auth_controllers->login();
}
//DB登録のエラーメッセージを取得
session_start();
if($_SESSION['errors']) {
	$errors = $_SESSION['errors'];
	$_SESSION = array();
}
session_destroy();
var_dump($user);
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
<p>
<?php if(isset($errors)) : ?>
	<?php foreach($errors as $error) : ?>
		<p><?php echo $error; ?></p>
	<?php endforeach; ?>
<?php endif; ?></p>
<form action="login.php" method="post" id="login">
	<dl>
		<dt><label for="user">ID</label></dt>
		<dd><input type="text" name="user" value="<?php echo $_GET["user_id"]; ?>"></dd>
		<dt><label for="password">PASSWORD</label></dt>
		<dd><input type="password" name="password"></dd>
	</dl>
	<input type="submit" value="送信">
</form>
<br>
<a href="user-regist.php">サインアップ</a>