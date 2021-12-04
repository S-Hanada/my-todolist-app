<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
$auth_controllers = new AuthController();
$auth_controllers->signupConfirm();
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
<p>以下の内容でユーザーを登録します</p>
<form action="signup-do.php?urltoken=<?php echo $_GET['urltoken']; ?>" method="post">
	<dl>
		<dt>メールアドレス</dt>
		<dd><?php echo $_POST['email']; ?></dd>
	</dl>
	<dl>
		<dt>ID</dt>
		<dd><?php echo $_POST['id']; ?></dd>
	</dl>
	<dl>
		<dt>登録名</dt>
		<dd><?php echo $_POST['name']; ?></dd>
	</dl>
	<dl>
		<dt>PASSWORD</dt>
		<dd><?php echo str_repeat('*', mb_strlen($_POST['password1'], 'UTF-8')); ?></dd>
	</dl>
	<input type="hidden" name="id" value="<?php echo htmlspecialchars($_POST['id'], ENT_QUOTES); ?>">
	<input type="hidden" name="name" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>">
	<input type="hidden" name="password1" value="<?php echo htmlspecialchars($_POST['password1'], ENT_QUOTES); ?>">
	<input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>">
	<input type="submit" value="登録">
</form>
<div>
	<a href="javascript:history.back()">前に戻る</a>
</div>
<?php require(__DIR__.'/../todo/footer.php'); ?>