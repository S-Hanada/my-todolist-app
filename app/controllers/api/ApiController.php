<?php
//ベースのコントローラーファイルを取得
require_once(__DIR__.'/../BaseController.php');
//todosテーブル用のモデルファイルを取得
require_once(__DIR__.'/../../models/Todo.php');
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../../models/User.php');
//バリデーションを取得
require_once(__DIR__.'/../../validations/TodoValidation.php');

//Todoリストに関するコントロール処理をまとめたクラス
class ApiController extends BaseController {

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

	//csvの出力先のフルパスを定義
	const CSVDIR = '../csv/';
	//CSVダウンロードメソッド
	public function csv($user) {
		//フルパス
		$file = self::CSVDIR.'task_list_'.date("YmdHis").'.csv';
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
		$response['filename'] = $filename;
		$response['date'] = date("Y年m月d日H時i分s秒");
		return $response;
	}

	//CSVダウンロードメソッド
	public function csvdownload($user) {
		//ブラウザからダウンロードできるように
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename={$filename}");
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		readfile($file);
	}
}