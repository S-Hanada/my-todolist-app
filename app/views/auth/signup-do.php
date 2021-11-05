<?php
//login用のコントローラーファイルを取得
require_once(__DIR__.'/../../controllers/AuthController.php');
//関数ファイルを読み込む
require_once(__DIR__.'/../../lib/util.php');
$auth_controllers = new AuthController();
//ユーザーを登録する
$auth_controllers->store();
if($_SESSION['errors']) {
	$errors = $_SESSION['errors'];
	$_SESSION = array();
}
if($_SESSION['success']) {
	$success = $_SESSION['success'];
	$_SESSION = array();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ユーザー本登録完了</title>
</head>
<body>
<?php if(isset($success)) : ?>
	<h1><?php echo $success; ?><h1>
	<p>ユーザーの登録が完了しました。ログインページからTodoリストサービスをご利用ください</p>
<?php else : ?>
	<h1><?php echo $errors; ?></h1>
	<p>メールの送信に失敗しました。サイト管理者にお問い合わせください</p>
<?php endif; ?>
<div>
	<a href="<?php echo sshJudge(); ?>/views/todo/">ログインする</a>
</div>
<?php require(__DIR__.'/../todo/footer.php'); ?>