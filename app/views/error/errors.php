<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
session_start();
if($_SESSION['error']) {
	$error = $_SESSION['error'];	
	unset($_SESSION['error']);
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $error; ?></title>
</head>
<body>
<h1><?php echo $error; ?></h1>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
<?php require_once(__DIR__.'/../todo/footer.php'); ?>