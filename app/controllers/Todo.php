<?php
//モデルファイルを取得
require_once('../../models/Todo.php');
//Todoリストに関するコントロール処理をまとめたクラス
class TodoControllers {

	public function index() {
		//modelファイルのfindAllメソッドからインデックスに表示する情報を取得
		$todos = Todo::findAll();
		return $todos;
	}
}
?>