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

		try {
			$pdo = new PDO($dsn, $user, $passward);
			//SQLの実行エラーがあった時、例外をスロー
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		$stmt = $dbh->query($sql);
		//カラム名をキーとして連想配列で全て取得するよう設定
		$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $todos;
	}

	//該当するtodoレコードを取得
	public static function findById($id) {
		//DB接続
		$dbh = self::DbConnect();
		//引数で渡されたtodoのidから該当するtodoを取得
		$sql = "SELECT user_id, title, comment, status, created_at, updated_at FROM todos WHERE id = '$id'";
		$stmt = $dbh->query($sql);
		//カラム名をキーとして連想配列で一つ取得するよう設定
		$todo = $stmt->fetch(PDO::FETCH_ASSOC);
		return $todo;
	}

	//todosテーブルに存在するidを取得
	public static function findAllIds() {
		//DB接続
		$dbh = self::DbConnect();
		//sql文を定義
		$sql = "SELECT id FROM todos";
		$stmt = $dbh->query($sql);
		$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $todos;
	}
}
?>