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
}