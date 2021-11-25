<?php

//サインアップに対するバリデーション
class SignupValidation {

	public $errors = array();

	public function check($id, $name, $password1, $password2) {
		//入力項目に抜けがないか
		if(!empty($id) && !empty($name) && !empty($password1) && !empty($password2)) {

			//IDが英数字であるか
			if(!ctype_alnum($id)) {
				$errors['NotAlphanumer'] = "IDは英字または数字で入力してください"; 
			}

			//IDの文字数制限
			if(mb_strlen($id) > 8) {
				$errors['CharacterLimitID'] = "IDは8文字以内にしてください";
			}

			//登録名の文字数制限
			if(mb_strlen($name) > 10) {
				$errors['CharacterLimitName'] = "登録名が長すぎます";
			}

			//パスワードが確認用と一致しているか
			if($password1 === $password2) {
				//パスワードの文字数制限
				if(mb_strlen($password1) > 8) {
					$errors['CharacterLimitPassword'] = "パスワードが長すぎます";
				}
				
				if(!preg_match('/^[a-zA-Z0-9]+$/', $password1)) {
					$errors['NotAlphanumer'] = "パスワードは英数字で入力してください"; 
				}
			} else {
				$errors['NotMatchPassword'] = "入力した2つのパスワードが一致しません";
			}

		} else {
			$errors['EmptyId'] = "未記入の項目があります"; 
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