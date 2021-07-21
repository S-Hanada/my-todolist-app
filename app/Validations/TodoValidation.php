<?php

//各種パラメーターに対するバリデーション
class TodoValidation {

	public static $errors = array();

	public static function check($title, $comment) {
		//タイトルは20文字以内
		if(mb_strlen($title) > 20) {
			self::$errors['chara-limit20'] = 'タイトルは20文字以内に収めてください。'; 
			return false;
		}
		//コメントは100文字以内
		if(mb_strlen($comment) > 100) {
			self::$errors['chara-limit100'] = '詳細は100文字以内に収めてください。';
			return false;
		}
		return true;
	}

	public static function getErrorMessage() {
		return self::$errors;
	}
}
?>