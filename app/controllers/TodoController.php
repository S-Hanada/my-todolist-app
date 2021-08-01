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
		$title = $_POST['title'];
		$comment = $_POST['comment'];
		//パラメーターのバリデーション
		$todo_validation = new TodoValidation();
		if(!$todo_validation->check($title, $comment)) {
			session_start();
			//エラーをセッションに格納
			$_SESSION['errors'] = $todo_validation->getErrorMessage();
			//リダイレクト先にPOSTデータを渡す307を指定
			header('Location: ../todo/new.php', true, 307);
			exit();
		}
		if(!Todo::save($user, $title, $comment)) {
			session_start();
			//エラーをセッションに格納
			$_SESSION['error'] = "登録に失敗しました";
			//リダイレクト先にPOSTデータを渡す307を指定
			header('Location: ../todo/new.php', true, 307);
			exit();
		}
	}

	public function update() {
		//GETパラメーターから編集する記事idを取得
		$id = $_GET['todo_id'];
		//POSTパラメーターから書き換えたフォームの内容を取得
		$title = $_POST['title'];
		$comment = $_POST['comment'];
		if(isset($_POST['status'])) {
			$status = $_POST['status'];
		} else {
			$status = "0";
		}
		//パラメーターのバリデーション
		$todo_validation = new TodoValidation();
		session_start();
		if(!$todo_validation->check($title, $comment)) {
			//エラーをセッションに格納
			$_SESSION['errors'] = $todo_validation->getErrorMessage();
			header('Location: ../todo/edit.php?todo_id='.$id, true, 307);
			exit();
		}
		if(!Todo::apply($id, $title, $comment, $status)) {
			//エラーをセッションに格納
			$_SESSION['error'] = "編集に失敗しました";
			header('Location: ../todo/edit.php?todo_id='.$id, true, 307);
			exit();
		}
	}


}
?>