<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TODOリスト</title>
</head>
<body>
<h1>TODO新規作成 確認ページ</h1>
<?php if($_POST['title']) : ?>
	<p>以下の内容を登録します。</p>
	<form action="new-result.php" method="post">
		<dl>
			<dt>タイトル</dt>
			<dd><?php echo htmlspecialchars($_POST['title']); ?></dd>
		</dl>
		<dl>
			<dt>詳細</dt>
			<dd><?php echo htmlspecialchars($_POST['comment']); ?></dd>
		</dl>
		<input type="hidden" name="title" value="<?php echo htmlspecialchars($_POST['title']); ?>">
		<input type="hidden" name="comment" value="<?php echo htmlspecialchars($_POST['comment']); ?>">
		<input type="submit" value="登録">
	</form>
<?php else : ?>
	<p>タイトルが未入力です。</p>
	<div>
		<a href="javascript:history.back()">前に戻る</a>
	</div>
<?php endif; ?>
</html>