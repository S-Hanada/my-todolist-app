<?php
//ベースのコントローラーファイルを取得
require_once(__DIR__.'/BaseController.php');
//todosテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/Todo.php');
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/User.php');
//Todoバリデーションを取得
require_once(__DIR__.'/../validations/TodoValidation.php');

//Todoリストに関するコントロール処理をまとめたクラス
class TodoController extends BaseController {

	public $errors = [];

	public function index($user) {
		//引数で渡されたtodoのidから該当するtodoを取得
		$params = [];
		//ユーザーを取得
		$params['user'] = $user;
		//GETパラメーターから値を取得
		if($_GET['title']) {
			$params['title'] = "%{$_GET['title']}%";
		}
		if($_GET['status'] !== "none") {
			$params['status'] = $_GET['status'];
		}
		if(!$params['title'] && !$params['status']) {
			//modelファイルのfindAllメソッドからインデックスに表示する情報を取得
			$todos = Todo::findAll($user);
			return $todos;
		}
		//入力した値からクエリを生成
		$query = $this->buildQuery($params);
		//生成したクエリから検索
		$todos = TODO::findByQuery($query, $params);
		if(!$todos) {
			session_start();
			//エラーをセッションに格納
			$_SESSION['errors'] = "該当するタスクが見つかりませんでした";
			return $todos;
		}
		return $todos;
	}

	//findByQueryのwhere句を生成
	private function buildQuery($params) {
	    $query = "SELECT * FROM todos WHERE user_id = :user";

	    foreach ($params as $key => $param) {
	    	if ($key === "title") {
	    		$query = $query . " AND title like :title";
	    	}
		    if ($key === "status") {
		    	$query = $query . " AND status = :status";
		    }
	    }
	    return $query;
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

	public function store($user) {
		if(!User::findByUserId($user)) {
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

	//CSVダウンロードメソッド
	public function csv($user) {
		//時間を日本時間に指定
		date_default_timezone_set('Asia/Tokyo');
		//フルパスを定義
		$file = './csv/task_list_'.date("YmdHis").'.csv';
		//フルパスからディレクトリ部分を関数で取得
		$dir = dirname($file);
		//フルパスからファイル名のみを取得
		$filename = basename($file);
		//ディレクトリが無ければディレクトリを作成
		if(!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		//ユーザーIDからタスク一覧を取得
		$tasks = User::selectCsvData($user);
		//項目名
        $header = ["id", "title", "comment", "status", "created_at"];
        //配列の先頭へ
        array_unshift($tasks, $header);
		//書き込みモードでファイルを開く
		$op_file = fopen($file, 'w');
		foreach($tasks as $key => $task) {
			if($key !== 0) {
				if($task['status'] === "done") {
					$task['status'] = "完了";
				} else {
					$task['status'] = "未完了";
				}
			}
			$task = mb_convert_encoding($task, 'SJIS');
			$line = implode(',' , $task);
			fwrite($op_file, $line.PHP_EOL);
		}
		fclose($op_file);
		//ブラウザからダウンロードできるように
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename={$filename}");
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		//すべての処理が完了後、リダイレクト
		header('Location: ../todo/index.php', true, 307);
		exit();
	}
}
?>