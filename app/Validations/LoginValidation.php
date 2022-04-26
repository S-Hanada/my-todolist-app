<?php
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/User.php');

//サインアップに対するバリデーション
class LoginValidation {

	public $errors = array();
	//ロック時間（1時間)
	const LOGIN_LOCK_PERIOD = 3600;

	public function check($params = []) {

		//IDまたはパスワードがあるか
		if(empty($params['user']) || empty($params['password'])) {
			$errors['NotInputValue'] = "IDまたはパスワードが入力されていません"; 
		} else {
			//入力した情報からユーザーを取得
			$user = User::isExisByUser($params);

			//IDとパスワードが間違っているとき
			if(empty($user)) {
				$errors['NotExisUser'] = "存在しないユーザーです"; 
			}
		}

		//null以外であればプロパティに格納
		if(isset($errors)) {
			$this->errors = $errors;
			return false;
		}
		return true;
	}

	public function checkLockTime($locktime) {
		
		//残りロック時間を取得
		$locktime_diff = strtotime('now') - strtotime($locktime);

		if($locktime_diff < self::LOGIN_LOCK_PERIOD) {
			$errors['Lock'] = "こちらのアカウントはロックされています。1時間後に再度ログインしてください"; 
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