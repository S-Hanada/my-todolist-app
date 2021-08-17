<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//DB接続
$tasks = $todo_controllers->index();
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
<div id="errormsg"></div>
<ul>
<?php foreach ($tasks as $task) : ?>
	<li>
		<?php if($task['status'] === '1') : ?>
			<input type="checkbox" name="status" value="<?php echo $task['id']; ?>" class="checkbtn" checked>
		<?php else : ?>
			<input type="checkbox" name="status" value="<?php echo $task['id']; ?>" class="checkbtn">
		<?php endif; ?>
		<?php if($task['status'] === '1') : ?>
			<label id="status">完了</label>
		<?php else : ?>
			<label id="status">未完了</label>
		<?php endif; ?>
		<span id="upresult"></span>
		<a href="<?php echo sprintf('detatil.php?todo_id=%d', $task['id'])?>">
		<?php echo $task['title']; ?>
		</a>
	</li>
<?php endforeach; ?>
</ul>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/new.php">新規作成</a>
</div>
<?php require_once(__DIR__.'/footer.php'); ?>
