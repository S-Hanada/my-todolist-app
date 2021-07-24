<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//エラ〜メッセージを取得
session_start();
if($_SESSION['errors']) {
	$errors = [];
	$errors = $_SESSION['errors'];	
	unset($_SESSION['errors']);
}
session_destroy();
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
<form action="confirm.php" method="post" id="new-form">
	<dl>
		<dt><label>タイトル</label></dt>
		<dd>
			<input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'], ENT_QUOTES); ?>">
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
		<dd><input type="text" name="comment" value="<?php echo htmlspecialchars($_POST['comment'], ENT_QUOTES); ?>">
		<?php if($errors['comment']) : ?>
			<ul>
				<?php foreach($errors['comment'] as $error) : ?>
					<li><?php echo $error; ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		</dd>
	</dl>
	<input type="submit" value="確認">
</form>
<div>
	<a href="<?php echo $todo_controllers->sshJudge(); ?>/views/todo/">一覧ページに戻る</a>
</div>
<?php require_once(__DIR__.'/footer.php'); ?>