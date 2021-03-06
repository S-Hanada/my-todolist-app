<?php
//DB接続用のクラスBaseModelを呼び出し
require_once(__DIR__.'/BaseModel.php');

class Todo extends BaseModel {

	//statusカラムの値を定数で定義
	const STATUS_YET = "yet";
	const STATUS_DONE = "done";

	//レコードの取得
	public static function findAll($user) {
		//DB接続
		$dbh = self::DbConnect();
		//sql文を定義
		$sql = "SELECT id, title, status FROM todos WHERE user_id = '$user'";
		$stmt = $dbh->query($sql);
		//カラム名をキーとして連想配列で全て取得するよう設定
		$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $todos;
	}

	//該当するtodoレコードを取得
	public static function findByTodo($id) {
		//DB接続
		$dbh = self::DbConnect();
		//引数で渡されたtodoのidから該当するtodoを取得
		$sql = "SELECT * FROM todos WHERE id = '$id'";
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

	//todosテーブルにidがあるかチェック。
	public static function isExisById($id) {
		return in_array($id, array_column(self::findAllIds(), 'id'));
	}

    //todosテーブルにレコードを挿入
	public static function save($user_id, $title, $comment = null) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
		
			$sql = "INSERT INTO todos (user_id, title, comment) VALUES (:user_id, :title, :comment)";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':user_id', $user_id);
			$stmt->bindValue(':title', $title);
			$stmt->bindValue(':comment', $comment);
			$stmt->execute();
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

	//todosテーブルにレコードをアップデート
	public static function update($id, $title, $comment = null, $status) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
		
			$sql = "UPDATE todos SET title = :title, comment = :comment, status = :status WHERE id = :id";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':title' => $title,
				':comment' => $comment,
				':status' => $status,
				':id' => $id
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

	//該当するidのstatusをを取得
	public static function findStatus($id) {
		//DB接続
		$dbh = self::DbConnect();
		//引数で渡されたtodoのidから該当するtodoを取得
		$sql = "SELECT status FROM todos WHERE id = '$id'";
		$stmt = $dbh->query($sql);
		//statusカラムを単一で取得
		$todo = $stmt->fetchColumn();
		return $todo;
	}

	//statusをajax通信でアップデート
	public static function statusUpdate($id, $status) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			$sql = "UPDATE todos SET status = :status WHERE id = :id";
			$stmt = $dbh->prepare($sql);
			// 更新する値と該当のIDを配列に格納する
			$params = array(
				':status' => $status,
				':id' => $id
			);
			$stmt->execute($params);
			// トランザクション完了
			$dbh->commit();
			//UPDATEでtrueを返すため、rowCountで直近のステートメントで作用した行数を取得
			$count = $stmt->rowCount();
			//行数があればtrueを返す
			if($count) {
				return true;
			}
		} catch (PDOException $e) {
			//トランザクション取り消し（ロールバック）
			$dbh->rollBack();
		}
		return false;
	}

	//statusをajax通信でアップデート
	public static function delete($id) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
		
			$sql = "DELETE FROM todos WHERE id = '$id'";
			$stmt = $dbh->query($sql);
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

	//該当する記事を取得
	public static function findByQuery($sql, $bind_values = []) {
		//DB接続
		$dbh = self::DbConnect();
		try {
			//トランザクション開始
			$dbh->beginTransaction();
			$stmt = $dbh->prepare($sql);
			$stmt->execute($bind_values);
			// // トランザクション完了
			$dbh->commit();
			$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $todos;
		} catch (PDOException $e) {
			//トランザクション取り消し（ロールバック）
			$dbh->rollBack();
			return false;
		}
	}
}
?>