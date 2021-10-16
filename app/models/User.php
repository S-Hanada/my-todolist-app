<?php
//DB接続用のクラスBaseModelを呼び出し
require_once(__DIR__.'/BaseModel.php');

//usersテーブルに対する処理をまとめたクラス
class User extends BaseModel {

	//todosテーブルに存在するidを取得
	public static function findByUserId($user) {
		//DB接続
		$dbh = self::DbConnect();
		//sql文を定義
		$sql = "SELECT id FROM users WHERE id = '$user'";
		$stmt = $dbh->query($sql);
		$user_id = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user_id;
	}

	//ユーザーを照合
	public static function isExisByUser($bind_values = []) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			//sql文を定義
			$sql = "SELECT id FROM users WHERE id = :user AND password = :password";
			$stmt = $dbh->prepare($sql);
			$stmt->execute($bind_values);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			$dbh->commit();
			return $user;
		} catch (PDOException $e) {
			//トランザクション取り消し（ロールバック）
			$dbh->rollBack();
			return;
		}
		//DB切断
		$dbh = null;
	}
}
?>