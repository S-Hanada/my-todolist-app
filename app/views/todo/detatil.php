<?php
//コントローラーファイルを取得
require_once('../../controllers/TodoController.php');
//関数ファイルを読み込む
require_once(__DIR__.'/../../lib/util.php');
session_start();
$todo_controllers = new TodoController();
$todo = $todo_controllers->detatil();
?>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $todo['title']; ?></title>
</head>
<body>
<h1><?php echo $todo['title']; ?></h1>
<p><?php echo $todo['comment']; ?></p>
<p>
	<span>作成日：</span><time datetime="<?php echo $todo['created_at']; ?>"><?php echo $todo['created_at']; ?></time>
	<?php if(strtotime($todo['created_at']) < strtotime($todo['updated_at'])) : ?>
		<span>更新日：<span><time datetime="<?php echo $todo['updated_at']; ?>"><?php echo $todo['updated_at']; ?></time>
	<?php endif; ?>
</p>
<?php if($todo['status'] === '1') : ?>
	<p>完了</p>
<?php else :?>
	<p>未完了</p>
<?php endif; ?>
<div>
	<a href="<?php echo sprintf('edit.php?todo_id=%d', $_GET['todo_id'])?>">編集する</a>
</div>
<div>
	<a href="<?php echo sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
<?php require(__DIR__.'/footer.php'); ?>