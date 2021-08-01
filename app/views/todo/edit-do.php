<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//編集機能を呼び出し
$todo_controllers->update();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TODOリスト</title>
</head>
<body>
<h1>編集完了しました。</h1>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/edit.php?todo_id=<?php echo $_GET['todo_id']; ?>">編集ページに戻る</a>
</div>
<?php require(__DIR__.'/footer.php'); ?>