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

	public function sendMail() {
		if(!$_POST['email']) {
			session_start();
			$_SESSION['errors'] = "メールアドレスが未入力です";
			header("Location: ../auth/user-regist.php");
			exit();
		}
		$email = $_POST['email'];
		//ユーザーが登録済みか
		$user = User::isExisByMaill($email);
		if($user) {
			session_start();
			$_SESSION['errors'] = "登録済みのメールアドレスです";
			header("Location: ../auth/user-regist.php");
			exit();
		}
		//urlに使用するトークンを発行
		$token = hash('sha256',uniqid(rand(),1));
		$urltoken = "http://localhost:8000/views/auth/signup.php?urltoken=".$token;
		// mb_language("Japanese");
		// mb_internal_encoding("UTF-8");
		$title = "Todoリスト ユーザー登録確認用メール";
		$message = "下記URLにログインし、本登録を行ってください".PHP_EOL.PHP_EOL;
		$message .= $urltoken.PHP_EOL;
		$headers = "From: from@example.com";
		$headers = "From: http://localhost:8000/";
		if(!mb_send_mail($email, $title, $message, $headers)) {
			session_start();
			$_SESSION['errors'] = "メールの送信に失敗しました";
			header("Location: ../auth/sendmail.php");
			exit();
		}
		session_start();
		$_SESSION['success'] = "仮登録完了";
		header("Location: ../auth/sendmail.php");
		exit();
	}
}
?>