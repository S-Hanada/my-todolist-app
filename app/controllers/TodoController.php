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
		$todo = Todo::findByTodo($todo_id);
		return $todo;
	}

	public function edit() {
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
		$todo = Todo::findByTodo($todo_id);
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
			header('Location: ../todo/new.php?title='.$title.'&comment='.$comment);
			exit();
		}
		if(!Todo::save($user, $title, $comment)) {
			session_start();
			//エラーをセッションに格納
			$_SESSION['error'] = "登録に失敗しました";
			//リダイレクト先にPOSTデータを渡す307を指定
			header('Location: ../todo/new.php?title='.$title.'&comment='.$comment);
			exit();
		}
	}

	public function update() {
		//POSTパラメーターから編集する記事idを取得
		$id = $_POST['todo_id'];
		//POSTパラメーターから書き換えたフォームの内容を取得
		$title = $_POST['title'];
		$comment = $_POST['comment'];
		if(isset($_POST['status'])) {
			$status = $_POST['status'];
		} else {
			$status = TODO::STATUS_YET;
		}
		//パラメーターのバリデーション
		$todo_validation = new TodoValidation();
		session_start();
		if(!$todo_validation->check($title, $comment)) {
			//エラーをセッションに格納
			$_SESSION['errors'] = $todo_validation->getErrorMessage();
			header('Location: ../todo/edit.php?todo_id='.$id.'&title='.$title.'&comment='.$comment.'&status='.$status, true, 307);
			exit();
		}
		if(!Todo::update($id, $title, $comment, $status)) {
			//エラーをセッションに格納
			$_SESSION['error'] = "編集に失敗しました";
			header('Location: ../todo/edit.php?todo_id='.$id.'&title='.$title.'&comment='.$comment.'&status='.$status, true, 307);
			exit();
		}
	}


	public function statusUpdate($id) {
		$response = array();
		$errormsg = "アップデートに失敗しました";
		//パラメーターのバリデーション
		$todo_validation = new TodoValidation();
		if(!$todo_validation->checkId($id)) {
			$response['result'] = "fail";
			$response['msg'] = $errormsg;
			return $response;
		}
		//idからDBのstatusの値を取得
		$status = Todo::findStatus($id);
		//DBに上書きするステータスを格納
		if($status === TODO::STATUS_DONE) {
			$status = TODO::STATUS_YET;
			$response['status'] = TODO::STATUS_YET;
		} else {
			$status = TODO::STATUS_DONE;
			$response['status'] = TODO::STATUS_DONE;
		}
		if(!Todo::statusUpdate($id, $status)) {
			$response['result'] = "fail";
			$response['msg'] = $errormsg;
			return $response;
		}
		$response['result'] = "success";
		return $response;
	}

	public function delete($id) {
		$response = array();
		$errormsg = "削除失敗しました";
		//パラメーターのバリデーション
		$todo_validation = new TodoValidation();
		if(!$todo_validation->checkId($id)) {
			$response['msg'] = $errormsg;
			return $response;
		}
		if(!Todo::delete($id)) {
			$response['msg'] = $errormsg;
			return $response;
		}
		$response['msg'] = "タスクの削除が完了しました";
		return $response;
	}
}
?>