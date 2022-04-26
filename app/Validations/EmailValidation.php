<?php

//サインアップに対するバリデーション
class EmailValidation {

	public $errors = array();

	public function check($email, $user) {
		//アドレスがあるか
		if(isset($email)) {
			//正規表現でアドレスのバリデーション
			$pattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD";
			if(!preg_match($pattern, $email)){
				$errors['PatternTrue'] = "メールアドレスではありません"; 
			}

			//usersテーブルに登録されているユーザーかのバリデーション
			if($user) {
				$errors['RegistUser'] = "登録済みのメールアドレスです";
			}
		} else {
			//未記入かのバリデーション
			$errors['EmptyAddres'] = "メールアドレスが未入力です";
		}
		
		//null以外であればプロパティに格納
		// var_dump($errors."ある");
		if(isset($errors)) {
			$this->errors = $errors;
			return false;
		}
		return true;
	}

	public function checkUrl($email) {
		if(!$email) {
			//未記入かのバリデーション
			$errors['EmptyUrl'] = "このURLはご利用できません。有効期限が過ぎたかURLが間違えている可能性がございます。もう一度登録をやrsaりなおして下さい。";
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