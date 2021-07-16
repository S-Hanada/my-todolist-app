<?php
//モデルファイルを取得
require_once(__DIR__.'/../models/Todo.php');

//Todoリストに関するコントロール処理をまとめたクラス
class TodoController {

	public function index() {
		//modelファイルのfindAllメソッドからインデックスに表示する情報を取得
		$todos = Todo::findAll();
		return $todos;
	}

	public function detatil() {
		//GETパラメーターからtodo_idを取得
		$todo_id = $_GET['todo_id'];
		//$todo_idに値がない場合
		if(!$todo_id) {
			header('Location: ../error/404.php');
			exit();
		}
		//$todo_idがtodosテーブルに存在しない場合は404.phpへ
		if(!Todo::isExisById($todo_id)) {
			header('Location: ../error/404.php');
			exit();
		}
		//modelファイルのfindByIdメソッドから該当するtodoレコードを取得
		$todo = Todo::findById($todo_id);
		return $todo;
	}

	public function addNewTask() {
		//ユーザーを取得
		$user = 'user001';
		if(!Todo::userCheck($user)) {
			echo '存在しないユーザーIDです';
			exit();
		}
		//POSTパラメーターから新規作成フォームの内容を取得
		$todo_title = $_POST['title'];
		$todo_comment = $_POST['comment'];
		Todo::addNewRecord($user, $todo_title, $todo_comment);
		return;
	}

	//URLがsshかを判定し、ドメインを繋げて出力
	public function sshJudge() {
		if (empty($_SERVER['HTTPS'])) {
			$protocol = "http://";
		} else {
			$protocol = "https://";
		}
		$url = $protocol.$_SERVER['HTTP_HOST'];
		return $url;
	}
}
?>