<?php
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../../models/User.php');

class AuthController {
	//URLがsshかを判定し、ドメインを繋げて出力
	public function login() {
		$params = [];
		if($_POST['user']) {
			$params['user'] = $_POST['user'];
		}
		if($_POST['password']) {
			$params['password'] = $_POST['password'];
		}
		$user = User::isExisByUser($params);
		if(!$user) {
			session_start();	
			$_SESSION['NotExisUser'] = "存在しないユーザーです";
			return false;
		}
		session_start();
		$_SESSION['user'] = $user['id'];
		$_SEESION['password'] =	$user['password'];
		return true;
	}
}
?>