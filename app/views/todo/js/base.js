//厳格なエラー表示を宣言
'use strict';

//新規作成画面で、タイトルが空欄だった時にアラートを表示
$(function() {
  $("#new-form").submit(function(){
    if($("input[name='title']").val() == '') {
      alert('タイトルは必ず入力してください');
      return false;
    }
  });
});