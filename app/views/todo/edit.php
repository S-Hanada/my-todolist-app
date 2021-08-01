<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//POSTパラメーターに値があれば
if(isset($_POST['title']) || isset($_POST['comment']) || isset($_POST['status'])) {
	// //エラ〜メッセージを取得
	session_start();
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
	session_destroy();
}
$todo = $todo_controllers->detatil();
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
<form action="edit-do.php?todo_id=<?php echo $_GET['todo_id']; ?>" method="post" id="update-form">
	<dl>
		<dt><label>タイトル</label></dt>
		<dd>
			<input type="text" name="title" value="<?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?>">
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
		<dd><input type="text" name="comment" value="<?php echo htmlspecialchars($todo['comment'], ENT_QUOTES); ?>">
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
			<?php if($todo['status'] === '1') : ?>
				<input type="checkbox" name="status" value="1" checked>
			<?php else : ?>
				<input type="checkbox" name="status" value="1">
			<?php endif; ?>
			<span>完了<span>
		</dd>
	</dl>
	<input type="submit" value="変更を保存">
</form>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
<?php require_once(__DIR__.'/footer.php'); ?>