<?php
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/User.php');

class AuthController {
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
			$_SESSION['errors'] = "存在しないユーザーです";
			//getパラメーターでフォームに返す
			header("Location: ../auth/login.php?user_id=".$params['user']);
			exit();
		}
		session_start();
		$_SESSION['user_id'] = $user['id'];
		header('Location: ../todo/index.php');
		exit();
	}
}
?>