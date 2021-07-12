<?php
//DB接続を親クラスとして定義
class BaseModel {

	//DBに接続する
	public function DbConnect() {
		//configファイルを読み込み
		$db = include('../../config/db.php');
		$dsn = sprintf('mysql:dbname=%s; host=%s; charset=%s', $db['DBNAME'], $db['DBHOST'], $db['DBCHAR']);
		$user = $db['DBUSER'];
		$passward = $db['DBPASS'];

		try {
			$pdo = new PDO($dsn, $user, $passward);
			//SQLの実行エラーがあった時、例外をスロー
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $pdo;
		} catch (PDOException $e) {
			return $e->getMessage();
		}
	}
}
?>