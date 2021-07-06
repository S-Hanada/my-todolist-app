<?php
//コントローラーファイルを取得
require_once('../../controllers/Todo.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoControllers();
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
	<?php echo $task['title']; ?>
	<?php if($task['status'] === '1') : ?>
		完了
	<?php else :?>
		未完了
	<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
</body>
</html>
