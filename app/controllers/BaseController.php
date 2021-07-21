<?php

class BaseController {
	
	//URLがsshかを判定し、ドメインを繋げて出力
	public function sshJudge() {
		if (empty($_SERVER['HTTPS'])) {
			$protocol = "http://";
		} else {
			$protocol = "https://";
		}
		$url = $protocol.$_SERVER['HTTP_HOST'];
		return $url;
	}
}
?>