<?php
//コントローラーファイルを取得
require_once('../../controllers/TodoController.php');

$todo_controller = new TodoController();
$todo_obj = $todo_controller->detatil();
//再利用しやすいよう一度変数に格納
foreach ($todo_obj as $todo) {
	$title = $todo['title'];
	$comment = $todo['comment'];
	$status = $todo['status'];
	$created_at = $todo['created_at'];
	$updated_at = $todo['updated_at']; 
}
?>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
</head>
<body>
<h1><?php echo $title; ?></h1>
<p><?php echo $comment; ?></p>
<p>
	<span>作成日：</span><time datetime="<?php echo $created_at; ?>"><?php echo $created_at; ?></time>
	<span>更新日：<span><time datetime="<?php echo $updated_at; ?>"><?php echo $updated_at; ?></time>
</p>
<?php if($status === '1') : ?>
	<p>完了</p>
<?php else :?>
	<p>未完了</p>
<?php endif; ?>
</body>
</html>