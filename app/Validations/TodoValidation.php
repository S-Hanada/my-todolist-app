<?php

//各種パラメーターに対するバリデーション
class TodoValidation {

	public $errors = array();

	public function check($title, $comment) {
		if($title) {
			//タイトルは20文字以内
			if(mb_strlen($title) > 20) {
				$errors1['CharaLimit20'] = 'タイトルは20文字以内に収めてください。'; 
			}
			//null以外であれば該当のキーの配列に格納
			if(isset($errors1)) {
				//title配列に格納
				$errors['title'] = $errors1;
			}
		}
		if($comment) {
			//コメントは100文字以内
			if(mb_strlen($comment) > 100) {
				$errors2['CharaLimit100'] = '詳細は100文字以内に収めてください。';
			}
			//null以外であれば該当のキーの配列に格納
			if(isset($errors2)) {
				//comment配列に格納
				$errors['comment'] = $errors2;
			}
		}
		//null以外であればプロパティに格納
		if(isset($errors)) {
			$this->errors = $errors;
			return false;
		}
		return true;
	}

	public function checkId($id) {
		if(empty($id)) {
			$errors['EmptyId'] = "Todoが取得できませんでした";
		}
		if(!Todo::isExisById($id)) {
			$errors['NotExisById'] = "存在しないTodoです。";
		}
		//null以外であればプロパティに格納
		if(isset($errors)) {
			$this->errors = $errors;
			return false;
		}
		return true;
	}

	public function getErrorMessage() {
		return $this->errors;
	}
}
?>