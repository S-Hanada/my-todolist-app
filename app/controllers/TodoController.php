<?php
//ベースのコントローラーファイルを取得
require_once(__DIR__.'/BaseController.php');
//todosテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/Todo.php');
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/User.php');
//バリデーションを取得
require_once(__DIR__.'/../validations/TodoValidation.php');

//Todoリストに関するコントロール処理をまとめたクラス
class TodoController extends BaseController {

	public $errors = [];

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

	public function store() {
		//ユーザーを取得
		$user = 'user003';
		if(!User::isExisByUserId($user)) {
			session_start();
			//エラーをセッションに格納
			$_SESSION['error'] = "存在しないユーザーIDです";
			header('Location: ../error/errors.php');
			exit();
		}
		//POSTパラメーターから新規作成フォームの内容を取得
		$todo_title = $_POST['title'];
		$todo_comment = $_POST['comment'];
		//パラメーターのバリデーション
		$todo_validation = new TodoValidation();
		if(!$todo_validation->check($todo_title, $todo_comment)) {
			session_start();
			//エラーをセッションに格納
			$_SESSION['errors'] = $todo_validation->getErrorMessage();
			//リダイレクト先にPOSTデータを渡す307を指定
			header('Location: ../todo/new.php', true, 307);
			exit();
		}
		Todo::addNewRecord($user, $todo_title, $todo_comment);
		return;
	}

}
?>