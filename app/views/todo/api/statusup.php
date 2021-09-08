<?php
//ステータス更新処理
//コントローラーファイルを取得
require_once(__DIR__.'/../../../controllers/api/ApiController.php');
//ApiControllerクラスをインスタンス
$todo_controllers = new ApiController();
// valueを取得
$id = filter_input(INPUT_POST, 'val');
//ステータスをアップデート
$response = $todo_controllers->statusUpdate($id);
header("Content-type: application/json; charset=UTF-8");
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>