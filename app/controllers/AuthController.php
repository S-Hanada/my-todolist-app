<?php
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/User.php');
//Eメール用のバリデーションを取得
require_once(__DIR__.'/../validations/EmailValidation.php');
//サインアップ確認用バリデーションを取得
require_once(__DIR__.'/../validations/SignupValidation.php');
//メール送信機能クラスを取得
require_once(__DIR__.'/../lib/mail.php');

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
		//フォームからメールアドレスを取得
		if($_POST['email']) {
			$email = $_POST['email'];
		}
		//ユーザーが登録済みか
		$user = User::isExisByMaill($email);
		//新規登録内容のバリデーション
		$email_validation = new EmailValidation();
		if(!$email_validation->check($email, $user)) {
			session_start();
			$_SESSION['errors'] = $email_validation->getErrorMessage();
			header("Location: ../auth/user-regist.php");
			exit();
		}
		$mail_system = new Mail();
		//サインアップ用の案内メッセージを配列で取得
		$mailinfo = $mail_system->buildSignupMailInfo($email);
		if(!$mail_system->send($mailinfo)) {
			session_start();
			$_SESSION['errors'] = "メールの送信に失敗しました";
			header("Location: ../auth/sendmail.php");
			exit();
		}
		//トークンとメールアドレスを一時的にDBに保存
		User::savePreUser($mailinfo['urltoken'], $mailinfo['email']);
		session_start();
		$_SESSION['token'] = $mailinfo['urltoken'];
		$_SESSION['success'] = "メールをお送りしました。10分以内にメールに記載されたURLからご登録下さい。";     ;
		header("Location: ../auth/sendmail.php");
		exit();
	}

	//発行されたURLとトークンを照合
	public function tokenMatching() {
		if(empty($_GET['urltoken'])) {
			header("Location: ../auth/user-regist.php");
			exit();
		}
		$urltoken = $_GET['urltoken'];
		//GETデータとDB内に保存されたトークンとを照合し合致したメールアドレスを抽出
		$email = User::isExisByToken($urltoken);
		//新規登録内容のバリデーション
		$email_validation = new EmailValidation();
		if(!$email_validation->checkUrl($email)) {
			session_start();	
			$_SESSION['errors'] = $email_validation->getErrorMessage();
			header("Location: ../auth/user-regist.php");
			exit();
		}
		return $email;
	}

	//発行されたURLとトークンを照合
	public function signupConfirm() {
		if(empty($_GET['urltoken'])) {
			header("Location: ../auth/user-regist.php");
			exit();
		}
		if($_POST['id']) {
			$id = $_POST['id'];
		}
		if($_POST['name']) {
			$name = $_POST['name'];
		}
		if($_POST['password1']) {
			$password1 = $_POST['password1'];
		}
		if($_POST['password2']) {
			$password2 = $_POST['password2'];
		}
		$signup_validation = new SignupValidation();
		if(!$signup_validation->check($id, $name, $password1, $password2)) {
			session_start();	
			$_SESSION['errors'] = $signup_validation->getErrorMessage();
			$urltoken = $_GET['urltoken'];
			header("Location: ../auth/signup.php?urltoken=".$urltoken);
			exit();
		}
	}

	//ユーザーを登録
	public function store() {
		if(empty($_GET['urltoken'])) {
			header("Location: ../auth/user-regist.php");
			exit();
		}
		$params = [];
		if($_POST['id']) {
			$params['id'] = $_POST['id'];
		}
		if($_POST['name']) {
			$params['name'] = $_POST['name'];
		}
		if($_POST['password1']) {
			$params['password'] = $_POST['password1'];
		}
		if($_POST['email']) {
			$params['email'] = $_POST['email'];
		}
		$urltoken = $_GET['urltoken'];
		//ユーザーを登録
		if(!User::save($params)) {
			session_start();
			$_SESSION['error'] = "ユーザー登録に失敗しました";
			header("Location: ../auth/signup.php?urltoken=".$urltoken);
			exit();
		}
		$mail_system = new Mail();
		//ユーザー登録完了のメールフォーマットを作成
		$mailinfo = $mail_system->buildSignupDoneMailInfo($params['email']);
		if(!$mail_system->send($mailinfo)) {
			session_start();
			$_SESSION['errors'] = "ユーザー登録中にエラーが起こりました";
			header("Location: ../auth/signup-do.php?urltoken=".$urltoken);
			exit();
		}
		session_start();
		$_SESSION['success'] = "ユーザー本登録完了";     ;
		//登録に成功した場合、仮登録したデータのflagをアップデートし参照対象から無効化
		User::updatePreUserFlag($params['email']);
	}
}
?>