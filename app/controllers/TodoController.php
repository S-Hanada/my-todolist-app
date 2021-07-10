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
		//$todo_idがない場合は404.phpへ
		if(!$this->isExisById($todo_id)) {
			header('Location: 404.php');
		}
		//modelファイルのfindByIdメソッドから該当するtodoレコードを取得
		$todo = Todo::findById($todo_id);
		return $todo;
	}

	//idがあるかチェック。
    public function isExisById($id) {
    	return in_array($id, array_column(Todo::findAllIds(), 'id'));
    }
}
?>