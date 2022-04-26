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
      url: "api/statusup.php",
      data: { val: $(this).val() },
      dataType : "json",
      context : this
    })
    // Ajaxリクエストが成功した場
    .done(function(data){
      //jsonオブジェクトの値で条件分岐し、一覧のstatusのラベルを切り替え
      if(data.status === "done") {
        $(this).next($("#status")).text("完了");
      } else {
        $(this).next($("#status")).text("未完了");
      }
      $(this).next($("#status")).next($("#upresult")).text(data.result);
      if(data.msg) {
        $("#resultmsg").text(data.msg);
      }
    })
    // Ajaxリクエストが失敗した場合
    .fail(function(XMLHttpRequest, textStatus, errorThrown){
      $(this).next($("#status")).text("fail");
      $("#resultmsg").text("アップデートに失敗しました");
    });
  });
});

// ajax delete
$(function() {
  $(".deletebtn").click(function(){
    //contextでthisを指定する事で、コールバック部分でもthisの値を使用できるように
    $.ajax({
      type: "POST",
      url: "api/delete.php",
      data: { val: $(this).val() },
      dataType : "json",
      context : this
    })
    // Ajaxリクエストが成功した場
    .done(function(data){
      //削除ボタンの親要素を削除
      $(this).parent().remove();
      if(data.msg) {
        $("#resultmsg").text(data.msg);
      }
    })
    // Ajaxリクエストが失敗した場合
    .fail(function(XMLHttpRequest, textStatus, errorThrown){
      $("#resultmsg").text("削除に失敗しました");
    });
  });
});

// ajax create csv
$(function() {
  $(".csvoutputbtn").click(function(){
    //contextでthisを指定する事で、コールバック部分でもthisの値を使用できるように
    $.ajax({
      type: "POST",
      url: "api/csv.php",
      data: { val: $(this).val() },
      dataType : "json",
      context : this
    })
    // Ajaxリクエストが成功した場
    .done(function(data){
      if(data.filename) {
        $("#resultmsg").text(data.filename);
      }
    })
    // Ajaxリクエストが失敗した場合
    .fail(function(XMLHttpRequest, textStatus, errorThrown){
      $("#resultmsg").text("CSVの作成に失敗しました");
    });
  });
});

// ajax csvdownload
$(function() {
  $(".csvdownload").click(function(){
    //contextでthisを指定する事で、コールバック部分でもthisの値を使用できるように
    $.ajax({
      type: "POST",
      url: "api/csvdownload.php",
      data: { val: $(this).val() },
      dataType : "json",
      context : this
    })
    // Ajaxリクエストが成功した場
    .done(function(data){
      if(data.filename) {
        $("#resultmsg").text(data.filename);
      }
    })
    // Ajaxリクエストが失敗した場合
    .fail(function(XMLHttpRequest, textStatus, errorThrown){
      $("#resultmsg").text("CSVの作成に失敗しました");
    });
  });
});