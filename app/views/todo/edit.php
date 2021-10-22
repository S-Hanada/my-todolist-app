<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//関数ファイルを読み込む
require_once(__DIR__.'/../../lib/util.php');
session_start();
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//フォームのエラ〜メッセージを取得
if($_SESSION['errors']) {
	$errors = [];
	$errors = $_SESSION['errors'];	
	unset($_SESSION['errors']);
}
//DB登録のエラーメッセージを取得
if($_SESSION['error']) {
	$dberror = $_SESSION['error'];
	unset($_SESSION['error']);
}
$todo = $todo_controllers->edit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TODOリスト</title>
</head>
<body>
<h1>TODO編集ページ</h1>
<?php if($dberror) : ?>
	<p style="color:red"><?php echo $dberror; ?><p>
<?php endif; ?>
<form action="edit-do.php" method="post" id="update-form">
	<dl>
		<dt><label>タイトル</label></dt>
		<dd>
			<?php if($_GET['title']) : ?>
				<input type="text" name="title" value="<?php echo htmlspecialchars($_GET['title'], ENT_QUOTES); ?>">
			<?php else : ?>
				<input type="text" name="title" value="<?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?>">
			<?php endif; ?>
			<?php if($errors['title']) : ?>
				<ul>
					<?php foreach($errors['title'] as $error) : ?>
						<li><?php echo $error; ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</dd>
	</dl>
	<dl>
		<dt><label>詳細</label></dt>
		<dd>
			<?php if($_GET['comment']) : ?>
				<input type="text" name="comment" value="<?php echo htmlspecialchars($_GET['comment'], ENT_QUOTES); ?>">
			<?php else : ?>
				<input type="text" name="comment" value="<?php echo htmlspecialchars($todo['comment'], ENT_QUOTES); ?>">
			<?php endif; ?>
		<?php if($errors['comment']) : ?>
			<ul>
				<?php foreach($errors['comment'] as $error) : ?>
					<li><?php echo $error; ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		</dd>
	</dl>
	<dl>
		<dt><label>タスク状況</label></dt>
		<dd>
			<?php if($_GET['status']) : ?>
				<?php if($_GET['status'] === 'done') : ?>
					<input type="checkbox" name="status" value="done" checked>
				<?php else : ?>
					<input type="checkbox" name="status" value="done">
				<?php endif; ?>
			<?php else : ?>
				<?php if($todo['status'] === 'done') : ?>
					<input type="checkbox" name="status" value="done" checked>
				<?php else : ?>
					<input type="checkbox" name="status" value="done">
				<?php endif; ?>
			<?php endif; ?>
			<span>完了<span>
		</dd>
	</dl>
	<input type="hidden" name="todo_id" value="<?php echo htmlspecialchars($todo['id'], ENT_QUOTES); ?>">
	<input type="submit" value="変更を保存">
</form>
<div>
	<a href="<?php echo sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
<?php require_once(__DIR__.'/footer.php'); ?>