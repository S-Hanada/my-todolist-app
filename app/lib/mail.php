<?php
class Mail {
	public function send($mailinfo = []) {
		//urlに使用するトークンを発行
		$email = $mailinfo['email'];
		$title = $mailinfo['title'];
		$message = $mailinfo['message'];
		$headers = $mailinfo['headers'];
		if(!mb_send_mail($email, $title, $message, $headers)) {
			return false;
		}
		return true;
	}

	//ユーザー仮登録用のメッセージ作成
	public function buildSignupMailInfo($email) {
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

	//ユーザー登録完了のメッセージ作成
	public function buildSignupDoneMailInfo($email) {
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