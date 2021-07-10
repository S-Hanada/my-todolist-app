<?php
//コントローラーファイルを取得
require_once('../../controllers/TodoController.php');

$todo_controller = new TodoController();
$todo = $todo_controller->detatil();
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
	<span>更新日：<span><time datetime="<?php echo $todo['updated_at']; ?>"><?php echo $todo['updated_at']; ?></time>
</p>
<?php if($todo['status'] === '1') : ?>
	<p>完了</p>
<?php else :?>
	<p>未完了</p>
<?php endif; ?>
</body>
</html>