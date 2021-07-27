<?php
//DB接続用のクラスBaseModelを呼び出し
require_once(__DIR__.'/BaseModel.php');

//usersテーブルに対する処理をまとめたクラス
class User extends BaseModel {

	//todosテーブルに存在するidを取得
	public static function isExisByUserId($user) {
		//DB接続
		$dbh = self::DbConnect();
		//sql文を定義
		$sql = "SELECT id FROM users WHERE id = '$user'";
		$stmt = $dbh->query($sql);
		$user_id = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user_id;
	}
}
?>