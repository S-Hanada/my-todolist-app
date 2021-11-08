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
	
}
?>