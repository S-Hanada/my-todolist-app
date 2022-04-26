<?php
//API用コントローラーファイルを取得
require_once(__DIR__.'/../../../controllers/api/ApiController.php');
//CSV作成処理
//ApiControllerクラスをインスタンス
$todo_controllers = new ApiController();
// valueを取得
$id = filter_input(INPUT_POST, 'val');
//タスクを削除
$response = $todo_controllers->csvdownload($id);
header("Content-type: application/json; charset=UTF-8");
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>