<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//関数ファイルを読み込む
require_once(__DIR__.'/../../lib/util.php');
session_start();
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//DB接続
$tasks = $todo_controllers->index($_SESSION['user_id']);
//DB登録のエラーメッセージを取得
if($_SESSION['errors']) {
	$errors = $_SESSION['errors'];
	unset($_SESSION['errors']);
}
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
<h2>タスクを検索する</h2>
<form action="index.php" method="get" id="search">
	<input type="text" name="title" value="<?php echo $_GET['title']; ?>">
	<select name="status" form="search">
		<option value="none" selected>選択してください</option>
		<option value="yet">未完了</option>
		<option value="done">完了</option>
	</select>
	<input type="submit" value="送信">
</form>
<h2>タスク一覧</h2>
<div id="resultmsg"></div>
<?php if($errors) : ?>
	<p><?php echo $errors; ?></p>
<?php else : ?>
	<ul>
	<?php foreach ($tasks as $task) : ?>
		<li>
			<?php if($task['status'] === 'done') : ?>
				<input type="checkbox" name="status" value="<?php echo $task['id']; ?>" class="checkbtn" checked>
			<?php else : ?>
				<input type="checkbox" name="status" value="<?php echo $task['id']; ?>" class="checkbtn">
			<?php endif; ?>
			<?php if($task['status'] === 'done') : ?>
				<label id="status">完了</label>
			<?php else : ?>
				<label id="status">未完了</label>
			<?php endif; ?>
			<span id="upresult"></span>
			<a href="<?php echo sprintf('detatil.php?todo_id=%d', $task['id'])?>">
			<?php echo $task['title']; ?>
			</a>
			<button type="button" class="deletebtn" value="<?php echo $task['id']; ?>" style="margin-left:20px">削除</button>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
<div>
	<a href="csv.php">
		<button lass="csvoutputbtn" value="<?php echo $task['id']; ?>">CSVで出力</button>
	</a>
</div>
<br>
<div>
	<a href="<?php echo sshJudge(); ?>/views/todo/new.php">新規作成</a>
</div>
<?php require_once(__DIR__.'/footer.php'); ?>
