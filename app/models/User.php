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
			$sql = "SELECT * FROM users WHERE id = :user AND password = :password";
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

	//ユーザーを照合
	public static function isExisByMail($email) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			//sql文を定義
			$sql = "SELECT id FROM users WHERE email = '$email'";
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
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

	//仮登録時に発行されたトークンと登録したメールアドレスを仮登録用のDBに格納。
	public static function savePreUser($urltoken, $email) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			//sql文を定義
			$sql = "INSERT INTO pre_user (urltoken, email, date, flag) VALUES (:urltoken, :email, now(), '0')";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$stmt->bindValue(':email', $email, PDO::PARAM_STR);
			$stmt->execute();
			return $dbh->commit();
		} catch (PDOException $e) {
			//トランザクション取り消し（ロールバック）
			$dbh->rollBack();
			return;
		}
		//DB切断
		$dbh = null;
	}

	//GETデータと照合するトークンとを照合し合致したメールアドレスを抽出
	public static function isExisByToken($urltoken) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			//flagが0の未登録者 or 仮登録日から24時間以内
			$sql = "SELECT email FROM pre_user WHERE urltoken=(:urltoken) AND flag = 0 AND date > now() - interval 2 hour";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$stmt->execute();
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

	//ユーザーを登録
	public static function save($bind_values = []) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			//sql文を定義
			$sql = "INSERT INTO users (id, password, name, email) VALUES (:id, :password, :name, :email)";
			$stmt = $dbh->prepare($sql);
			$stmt->execute($bind_values);
			return $dbh->commit();
		} catch (PDOException $e) {
			//トランザクション取り消し（ロールバック）
			$dbh->rollBack();
			return false;
		}
		//DB切断
		$dbh = null;
	}

	//仮登録のDBから該当するメールアドレスのflagを無効にする
	public static function updatePreUserFlag($email) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			$sql = "UPDATE pre_user SET flag = 1 WHERE email = :email";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':email' => $email
			);
			$stmt->execute($params);
			// トランザクション完了
			return $dbh->commit();
		} catch (PDOException $e) {
			//トランザクション取り消し（ロールバック）
			$dbh->rollBack();
			return false;
		}
		//DB切断
		$dbh = null;
	}

	public static function getLoginFailedCount($id) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			// トランザクションの開始
			$dbh->beginTransaction();
			$sql = "SELECT failed_count FROM users WHERE id = :id";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':id' => $id
			);
			$stmt->execute($params);
			$count = $stmt->fetch(PDO::FETCH_ASSOC);
			// コミット
			$dbh->commit();
			if (!empty($count)) {
				return $count['failed_count'];
			}
		} catch (PDOException $e) {
			// ロールバック
			$dbh->rollBack();
			return false;
		}
		//DB切断
		$dbh = null;
	}

	//ログイン失敗のカウントアップをする
	public static function loginFailedCountUp($id) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			// トランザクションの開始
			$dbh->beginTransaction();
			$sql = "UPDATE users SET failed_count = failed_count + 1 WHERE id = :id AND flag = 0";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':id' => $id
			);
			$stmt->execute($params);
			// コミット
			return $dbh->commit();
		} catch (PDOException $e) {
			// ロールバック
			$dbh->rollBack();
			return false;
		}
		//DB切断
		$dbh = null;
	}

	//アカウントのロック
	public static function lockLoginAccount($id) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			// トランザクションの開始
			$dbh->beginTransaction();
			$sql = "UPDATE users SET flag = 1, locked_time = now() WHERE id = :id AND flag = 0";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':id' => $id
			);
			$stmt->execute($params);
			// コミット
			return $dbh->commit();
		} catch (PDOException $e) {
			// ロールバック
			$dbh->rollBack();
			return false;
		}
		//DB切断
		$dbh = null;
	}

	//アカウントのアンロック
	public static function unlockLoginAccount($id) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			// トランザクションの開始
			$dbh->beginTransaction();
			$sql = "UPDATE users SET failed_count = 0, locked_time = NULL, flag = 0 WHERE id = :id AND flag = 1";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':id' => $id
			);
			$stmt->execute($params);
			// コミット
			return $dbh->commit();
		} catch (PDOException $e) {
			// ロールバック
			$dbh->rollBack();
			return false;
		}
		//DB切断
		$dbh = null;
	}

	//CSVデータを取得
	public static function selectCsvData($user) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			//sql文を定義
			$sql = "SELECT id, title, comment, status, created_at FROM todos WHERE user_id = :user";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':user' => $user
			);
			$stmt->execute($params);
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
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