<?php

class Todo {

	//DB接続用の設定情報を定義
	const DBNAME = 'todolist';
	const DBHOST = 'mysql';
	const DBCHAR = 'utf8mb4';
	const DBUSER = 'hanada';
	const DBPASS = 'hanada';

	//DBに接続する
	public function DbConnect() {
		$dsn = sprintf('mysql:dbname=%s; host=%s; charset=%s', self::DBNAME, self::DBHOST, self::DBCHAR);
		$user = self::DBUSER;
		$passward = self::DBPASS;
		$driver_options = array(
			//SQLの実行エラーがあった時、例外をスロー
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			//フェッチスタイルをカラム名をキーとして連想配列で取得するよう設定
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);

		try {
			$pdo = new PDO($dsn, $user, $passward, $driver_options);
			return $pdo;
		} catch (PDOException $e) {
			return $e->getMessage();
		}
	}

	//レコードの取得
	public static function findAll() {
		//DB接続
		$dbh = self::DbConnect();
		//ログインユーザーを取得（暫定固定）
		$user = "user001";
		//sql文を定義
		$sql = "SELECT id, title, status FROM todos WHERE user_id = '$user'";
		$todos = $dbh->query($sql);
		return $todos;
	}

	//該当するtodoレコードを取得
	public function findById($id) {
		//DB接続
		$dbh = self::DbConnect();
		//引数で渡されたtodoのidから該当するtodoを取得
		$sql = "SELECT title, comment, status, created_at, updated_at FROM todos WHERE id = '$id'";
		$todo = $dbh->query($sql);
		return $todo;
	}
}
?>