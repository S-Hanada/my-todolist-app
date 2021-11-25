<?php
//userテーブル用のモデルファイルを取得
require_once(__DIR__.'/../models/User.php');
//Eメール用のバリデーションを取得
require_once(__DIR__.'/../validations/EmailValidation.php');
//サインアップ確認用バリデーションを取得
require_once(__DIR__.'/../validations/SignupValidation.php');
//サインアップ確認用バリデーションを取得
require_once(__DIR__.'/../validations/LoginValidation.php');
//メール送信機能クラスを取得
require_once(__DIR__.'/../lib/mail.php');

class AuthController {

	//ログインがロックされるまでのリミット
	const LOGIN_FAILED_LIMIT = 3;

	function __construct() {
        date_default_timezone_set('Asia/Tokyo');
    }

	public function login() {
		$params = [];
		if($_POST['user']) {
			$params['user'] = $_POST['user'];
		}
		if($_POST['password']) {
			$params['password'] = $_POST['password'];
		}
		$login_validation = new LoginValidation();
		//入力情報のバリデーション
		if(!$login_validation->check($params)) {
			session_start();	
			$_SESSION['errors'] = $login_validation->getErrorMessage();
			//getパラメーターでフォームに返す
			header("Location: ../auth/login.php?user_id=".$params['user']);
			exit();
		}
		//入力した情報からユーザーを取得
		$user = User::isExisByUser($params);
		//ユーザー情報の有無のバリデーション
		if(!$login_validation->checkUser($user)) {
			session_start();
			$_SESSION['errors'] = $login_validation->getErrorMessage();
			$this->lock($params['user']);
			//getパラメーターでフォームに返す
			header("Location: ../auth/login.php?user_id=".$params['user']);
			exit();
		}
		//ログイン
		//ロックフラグが有効なもの
		if($user['flag'] === "1") {
			//残りロック時間を取得
			$locktime_diff = strtotime('now') - strtotime($user['locked_time']);
			if(!$login_validation->checkLockTime($locktime_diff)) {
				session_start();
	        	$_SESSION['errors'] = $login_validation->getErrorMessage();
				//getパラメーターでフォームに返す
				header("Location: ../auth/login.php?user_id=".$params['user']);
				exit();
			} else {
				// アカウントロック期間終了だったらロック解除
				User::unlockLoginAccount($params['user']);
			}
		}
		session_start();
		$_SESSION['user_id'] = $user['id'];
		header('Location: ../todo/index.php');
		exit();
	}

	//アカウントロック機能
	private function lock($id) {
		//カウントアップ
		User::loginFailedCountUp($id);
		//カウントを取得
		$count = User::getLoginFailedCount($id);
		//DB上に保存されている該当のIDが、失敗の上限を越えたらロック
		if($count >= self::LOGIN_FAILED_LIMIT) {
			User::lockLoginAccount($id);
		}
	}

	public function sendMail() {
		//フォームからメールアドレスを取得
		if($_POST['email']) {
			$email = $_POST['email'];
		}
		//ユーザーが登録済みか
		$user = User::isExisByMail($email);
		//新規登録内容のバリデーション
		$email_validation = new EmailValidation();
		if(!$email_validation->check($email, $user)) {
			session_start();
			$_SESSION['errors'] = $email_validation->getErrorMessage();
			header("Location: ../auth/user-regist.php");
			exit();
		}
		//サインアップ用の案内メッセージを配列で取得
		$mailinfo = $this->buildSignupMailInfo($email);
		//メールシステムのクラスを呼び出し
		$mail_system = new Mail();
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

	//ユーザー仮登録用のメッセージ作成
	private function buildSignupMailInfo($email) {
		//urlに使用するトークンを発行
		$mail = [];
		$mail['email'] = $email;
		$urltoken = hash('sha256',uniqid(rand(),1));
		$url = "http://localhost:8000/views/auth/signup.php?urltoken=".$urltoken;
		$mail['urltoken'] = $urltoken;
		$mail['url'] = $url;
		$mail['title'] = "Todoリスト ユーザー登録確認用メール";
		$message = "下記URLにアクセスし、本登録を行ってください".PHP_EOL.PHP_EOL;
		$message .= $url.PHP_EOL;
		$mail['message'] = $message;
		$headers = "From: from@example.com";
		// $headers = "From: http://localhost:8000/";
		$mail['headers'] = $headers;
		return $mail;
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
		//ユーザー登録完了のメールフォーマットを作成
		$mailinfo = $this->buildSignupDoneMailInfo($params['email']);
		//メールシステムのクラスを呼び出し
		$mail_system = new Mail();
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

	//ユーザー登録完了のメッセージ作成
	private function buildSignupDoneMailInfo($email) {
		//urlに使用するトークンを発行
		$mail = [];
		$mail['email'] = $email;
		$mail['title'] = "ユーザー登録が完了しました。";
		$url = "http://localhost:8000/views/auth/login.php";
		$message = "ユーザー登録が完了しました。下記URLからTodoリストページにログインを行なってください".PHP_EOL.PHP_EOL;
		$message .= $url.PHP_EOL;
		$mail['message'] = $message;
		$headers = "From: signup@example.com";
		// $headers = "From: http://localhost:8000/";
		$mail['headers'] = $headers;
		return $mail;
	}
}
?>