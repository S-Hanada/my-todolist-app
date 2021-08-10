//厳格なエラー表示を宣言
'use strict';

//新規作成画面で、タイトルが空欄だった時にアラートを表示
$(function() {
  $("#new-form,#update-form").submit(function(){
    if($("input[name='title']").val() == '') {
      alert('タイトルは必ず入力してください');
      return false;
    }
  });
});

// jQuery ajax
$(function() {
  $(".checkbtn").click(function(){
    //contextでthisを指定する事で、コールバック部分でもthisの値を使用できるように
    $.ajax({
      type: "POST",
      url: "application/json.php",
      data: { val: $(this).val() },
      dataType : "json",
      context : this
    })
    // Ajaxリクエストが成功した場
    .done(function(data){
      //jsonオブジェクトの値で条件分岐し、一覧のstatusのラベルを切り替え
      if(data.status === "1") {
        $(this).next($("#status")).text("完了");
        $(this).next($("#status")).next($("#upresult")).text("success");
      } else {
        $(this).next($("#status")).text("未完了");
        $(this).next($("#status")).next($("#upresult")).text("");
      }
    })
    // Ajaxリクエストが失敗した場合
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
      $(this).next($("#status")).text("リクエスト失敗");
      $(this).next($("#status")).next($("#upresult")).text("fail");
    });
  });
});