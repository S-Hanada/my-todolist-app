<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//関数ファイルを読み込む
require_once(__DIR__.'/../../lib/util.php');
session_start();
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//新規作成
$todo_controllers->store($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TODOリスト</title>
</head>
<body>
<h1>登録完了しました。</h1>
<div>
	<a href="<?php echo sshJudge(); ?>/views/todo/new.php">新規作成ページに戻る</a>
</div>
<?php require(__DIR__.'/footer.php'); ?>