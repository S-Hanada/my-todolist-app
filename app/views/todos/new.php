<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TODOリスト</title>
</head>
<body>
<h1>TODO新規作成ページ</h1>
<form action="confirm.php" method="post">
	<dl>
		<dt><label>タイトル</label></dt>
		<dd><input type="text" name="title"></dd>
	</dl>
	<dl>
		<dt><label>詳細</label></dt>
		<dd><input type="text" name="comment"></dd>
	</dl>
	<input type="submit" value="確認">
</form>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
</body>
</html>