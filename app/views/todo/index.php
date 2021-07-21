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
<ul>
<?php foreach ($tasks as $task) : ?>
	<li>
		<a href="<?php echo sprintf('detatil.php?todo_id=%d', $task['id'])?>">
		<?php echo $task['title']; ?>
		<?php if($task['status'] === '1') : ?>
			完了
		<?php else :?>
			未完了
		<?php endif; ?>
		</a>
	</li>
<?php endforeach; ?>
</ul>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/new.php">新規作成</a>
</div>
</body>
</html>
