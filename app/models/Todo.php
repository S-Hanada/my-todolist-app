<?php
//DB接続用のクラスBaseModelを呼び出し
require_once('BaseModel.php');

class Todo extends BaseModel {

	//レコードの取得
	public static function findAll() {
		//DB接続
		$dbh = self::DbConnect();
		//ログインユーザーを取得（暫定固定）
		$user = "user002";
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