<?php
//コントローラーファイルを取得
require_once(__DIR__.'/../../controllers/TodoController.php');
session_start();
//todoControllersクラスをインスタンス
$todo_controllers = new TodoController();
//DB接続
$tasks = $todo_controllers->csv($_SESSION['user_id']);