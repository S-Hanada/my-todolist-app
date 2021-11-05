<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
//関数ファイルを読み込む
require_once(__DIR__.'/../../lib/util.php');
$auth_controllers = new AuthController();
$status = $auth_controllers->tokenMatching();
session_start();
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
	<title>ユーザーを本登録する</title>
</head>
<body>
<h1>ユーザー本登録</h1>
<?php if(isset($errors)) : ?>
	<?php foreach($errors as $error) : ?>
		<p><?php echo $error; ?></p>
	<?php endforeach; ?>
<?php endif; ?>
<form action="signup-confirm.php?urltoken=<?php echo $_GET['urltoken']; ?>" method="post">
	<dl>
		<dt>メールアドレス</dt>
		<dd><?php echo $status['email']; ?></dd>
	</dl>
	<dl>
		<dt><label for="id">ID</label></dt>
		<dd><input type="text" name="id"></dd>
	</dl>
	<dl>
		<dt><label for="name">登録名</label></dt>
		<dd><input type="text" name="name"></dd>
	</dl>
	<dl>
		<dt><label for="password1">PASSWORD</label></dt>
		<dd><input type="password" name="password1"></dd>
	</dl>
	<dl>
		<dt><label for="password2">確認用</label></dt>
		<dd><input type="password" name="password2"></dd>
	</dl>
	<input type="hidden" name="email" value="<?php echo htmlspecialchars($status['email'], ENT_QUOTES); ?>">
	<input type="hidden" name="urltoken" value="<?php echo htmlspecialchars($_GET['urltoken'], ENT_QUOTES); ?>">
	<input type="submit" value="確認">
</form>
<br>
<a href="login.php">ログイン画面に戻る</a>
<?php require(__DIR__.'/../todo/footer.php'); ?>