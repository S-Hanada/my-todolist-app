<?php
//ステータス更新処理
//コントローラーファイルを取得
require_once(__DIR__.'/../../../controllers/TodoController.php');
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
// // //エラ〜メッセージを取得
// session_start();
// //フォームのエラ〜メッセージを取得
// if($_SESSION['errors']) {
//     $errors = [];
//     $errors = $_SESSION['errors'];  
//     unset($_SESSION['errors']);
// }
// //DB登録のエラーメッセージを取得
// if($_SESSION['error']) {
//     $dberror = $_SESSION['error'];
//     unset($_SESSION['error']);
// }
// session_destroy();
// valueを取得
$id = filter_input(INPUT_POST, 'val');
//ステータスをアップデート
$todo_controllers->statusUpdate($id);
//アップデート後のデータを取得
$todo = $todo_controllers->getAfterTodo($id);
header("Content-type: application/json; charset=UTF-8");
echo json_encode($todo, JSON_UNESCAPED_UNICODE);
// echo json_encode($errors, JSON_UNESCAPED_UNICODE);
exit;
?>