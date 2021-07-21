<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//質問部分：エラーコメントの配列が空
$errors = $todo_controllers->getErrorMessage();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- エラーの内容によって文言を変更したい -->
	<title>存在しないユーザーIDです</title>
</head>
<body>
<!-- エラーの内容によって文言を変更したい -->
<h1>存在しないユーザーIDです</h1>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
</body>
</body>
</html>