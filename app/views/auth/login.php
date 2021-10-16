<?php
session_start();
//DB登録のエラーメッセージを取得
if($_SESSION['NotExisUser']) {
	$not_exits = $_SESSION['NotExisUser'];
	unset($_SESSION['NotExisUser']);
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
<p><?php echo $not_exits; ?></p>
<form action="/views/todo/index.php" method="post" id="login">
	<dl>
		<dt><label for="user">ID</label></dt>
		<dd><input type="text" name="user" value="<?php echo $_POST["user"]; ?>"></dd>
		<dt><label for="password">PASSWORD</label></dt>
		<dd><input type="password" name="password"></dd>
	</dl>
	<input type="submit" value="送信">
</form>
