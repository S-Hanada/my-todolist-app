<?php
//モデルファイルを取得
require_once('../../models/Todo.php');

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
		if(!$this->isExisById($todo_id)) {
			header('Location: ../error/404.php');
			exit();
		}
		//modelファイルのfindByIdメソッドから該当するtodoレコードを取得
		$todo = Todo::findById($todo_id);
		return $todo;
	}

	//todosテーブルにidがあるかチェック。
    public function isExisById($id) {
    	return in_array($id, array_column(Todo::findAllIds(), 'id'));
    }
}
?>