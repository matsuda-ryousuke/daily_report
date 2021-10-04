$(function(){
  //　ブラウザのキャッシュ対策(スーパーリロード替わり)

  // 「エリアグループ」セレクトボックスの変更時イベント登録
  $("#area_group").on('change',function(){get_area()});
  $("#area_group").on('change',function(){get_client_by_area_group()});

  // 「エリア」セレクトボックスの変更時イベント登録
  $("#area").on('change',function(){get_client()});
  $("#area").on('change',function(){set_area_group_by_area()});

  // 「取引先」セレクトボックスの変更時イベント登録
  $("#client").on('change',function(){set_area()});
  $("#client").on('change',function(){set_area_group()});

  // リセットボタン登録時のイベント登録
  $("#btn-search-reset").click(function(){reset_area()});
  $("#btn-search-reset").click(function(){reset_client()});


  /**
   * ajax通信を用いてエリア情報を取得
   */
  function get_area(){
      var params = [6];
      // params[0] = '/get_user/dep/' + $("#bumon").val();
      params[0] = '/report/get_area/' + $("#area_group").val();
      params[1] = 'GET';
      params[2] = '';
      params[3] = function(data){

        if(data.length>0){

          $('#area')
            .prop('disabled', false)
            .empty();

            $('#area').append('<option selected value=0>全件取得</option>');
            $.each(data,
              function(index, val) {
                $('#area').append('<option value=' + val.area_id + '>' + val.name + '</option>');
              }
            )
        } else {
          $('#area')
          .prop('disabled', true)
          .empty();
        }

      }
      params[4] = function(data){};
      params[5] = function(data){};
      ajaxbase(params);
    }

    /**
   * ajax通信を用いてエリア情報を取得
   */
  function get_client_by_area_group(){
    var params = [6];
    // params[0] = '/get_user/dep/' + $("#bumon").val();
    params[0] = '/report/get_client_by_area_group/' + $("#area_group").val();
    params[1] = 'GET';
    params[2] = '';
    params[3] = function(data){

      if(data.length>0){

        $('#client')
          .prop('disabled', false)
          .empty();

          $('#client').append('<option selected value=0>全件取得</option>');
          $.each(data,
            function(index, val) {
              $('#client').append('<option value=' + val.client_id + '>' + val.company_name + '</option>');
            }
          )

      } else {
        $('#client')
        .prop('disabled', true)
        .empty();
      }

    }
    params[4] = function(data){};
    params[5] = function(data){};
    ajaxbase(params);
  }
  
    /**
     * ajax通信を用いて取引先情報を取得
     * 
     */
    function get_client(){
      var params = [6];
      params[0] = '/report/get_client/' + $("#area").val();
      params[1] = 'GET';
      params[2] = '';
      params[3] = function(data){
        if(data.length>0){
          $('#client')
            .prop('disabled', false)
            .empty();
            
            $('#client').append('<option selected value=0>全件取得</option>');
            $.each(data,
              function(index, val) {
                $('#client').append('<option value=' + val.client_id + '>' + val.company_name + '</option>');
              }
            )
            
        } else {
          $('#client')
          .prop('disabled', true)
          .empty();
        }
      }
      params[4] = function(data){};
      params[5] = function(data){};
      ajaxbase(params);
  
    }

    /**
       * ajax通信を用いて取引先情報を取得
       * 
       */
      function set_area_group_by_area(){
        var params = [6];
        params[0] = '/report/set_area_group_by_area/' + $("#area").val();
        params[1] = 'GET';
        params[2] = '';
        params[3] = function(data){
            $('#area_group')
              .prop('disabled', false)
            
              var option_area_group = document.querySelectorAll("select[name=area_group] option");
              if($("#area").val() != 0){
                for(var area_group of option_area_group) {
                  if(area_group.value === data) {
                    area_group.selected = true;
                  } else {
                    area_group.selected = false;
                  }
                }
              }

        }
        params[4] = function(data){};
        params[5] = function(data){};
        ajaxbase(params);
    
      }

      /**
       * ajax通信を用いて取引先情報を取得
       * 
       */
      function set_area(){
        var params = [6];
        params[0] = '/report/set_area/' + $("#client").val();
        params[1] = 'GET';
        params[2] = '';
        params[3] = function(data){
            $('#area')
              .prop('disabled', false)
            
              var option_area = document.querySelectorAll("select[name=area] option");
              if($("#client").val() != 0){
                for(var area of option_area) {
                  if(area.value === data) {
                    area.selected = true;
                  } else {
                    area.selected = false;
                  }
                }
              }

        }
        params[4] = function(data){};
        params[5] = function(data){};
        ajaxbase(params);
    
      }

      /**
       * ajax通信を用いて取引先情報を取得
       * 
       */
      function set_area_group(){
        var params = [6];
        params[0] = '/report/set_area_group/' + $("#client").val();
        params[1] = 'GET';
        params[2] = '';
        params[3] = function(data){
            $('#area_group')
              .prop('disabled', false)
            
              var option_area_group = document.querySelectorAll("select[name=area_group] option");
              if($("#client").val() != 0){
                for(var area_group of option_area_group) {
                  if(area_group.value === data) {
                    area_group.selected = true;
                  } else {
                    area_group.selected = false;
                  }
                }
              }

        }
        params[4] = function(data){};
        params[5] = function(data){};
        ajaxbase(params);
    
      }

      /**
   * ajax通信を用いてリセット時にエリア情報を取得
   */
  function reset_area(){
    var params = [6];
    // params[0] = '/get_user/dep/' + $("#bumon").val();
    params[0] = '/report/reset_area';
    params[1] = 'GET';
    params[2] = '';
    params[3] = function(data){

      if(data.length>0){

        $('#area')
          .prop('disabled', false)
          .empty();

          $('#area').append('<option selected value=0>全件取得</option>');
          $.each(data,
            function(index, val) {
              $('#area').append('<option value=' + val.area_id + '>' + val.name + '</option>');
            }
          )
      } else {
        $('#area')
        .prop('disabled', true)
        .empty();
      }

    }
    params[4] = function(data){};
    params[5] = function(data){};
    ajaxbase(params);
  }

  /**
 * ajax通信を用いてリセット時にクライアント情報を取得
 */
function reset_client(){
  var params = [6];
  // params[0] = '/get_user/dep/' + $("#bumon").val();
  params[0] = '/report/reset_client';
  params[1] = 'GET';
  params[2] = '';
  params[3] = function(data){

    if(data.length>0){

      $('#client')
        .prop('disabled', false)
        .empty();

        $('#client').append('<option selected value=0>全件取得</option>');
        $.each(data,
          function(index, val) {
            $('#client').append('<option value=' + val.client_id + '>' + val.company_name + '</option>');
          }
        )

    } else {
      $('#client')
      .prop('disabled', true)
      .empty();
    }

  }
  params[4] = function(data){};
  params[5] = function(data){};
  ajaxbase(params);
}


    /**
     * 検索ボックス内リセット処理
     */
    $('#btn-search-reset').click(function(){
      // ユーザーを初期化
      var element = document.getElementById('user');
      var elements = element.options;
      elements[0].selected = true;

      // エリアグループを初期化
      var element = document.getElementById('area_group');
      var elements = element.options;
      elements[0].selected = true;

      // 検索日付を初期化
      document.getElementById('after').value ="";
      document.getElementById('before').value ="";

      // 検索日付のmax,minを初期化
      // 1桁の数字を0埋めで2桁にする
      var toDoubleDigits = function(num) {
        num += "";
        if (num.length === 1) {
          num = "0" + num;
        }
      return num;     
      };
      // 日付をYYYY-MM-DD形式で取得
      var yyyymmdd = function() {
        var date = new Date();
        var yyyy = date.getFullYear();
        var mm = toDoubleDigits(date.getMonth() + 1);
        var dd = toDoubleDigits(date.getDate());
        return yyyy + '-' + mm + '-' + dd;
      }
      document.getElementById('before').min = "";
      document.getElementById('after').max = yyyymmdd();

      // キーワードを初期化
      document.getElementById('keyword').value ="";

      // チェックボックスを外す
      var element = document.getElementById("new");
      element.checked = false;

      // 新着期間を初期化　
      document.getElementById('newdays').value ="";
      document.getElementById('newdays').disabled = true;

      // ラジオボタン、downをtrueにしてupをfalseに
      var elementDown = document.getElementById("down");
      elementDown.checked = true;

      var elementUp = document.getElementById("up");
      elementUp.checked = false;

      

    });
    
    // 日付フォーム、afterが変更されたらbeforeの最小値を指定
    document.getElementById("after").onchange = function() { 
      var date = document.getElementById("after").value;
      document.getElementById('before').min = date;
    };

    // 日付フォーム、beforeが変更されたらafterの最大値を指定
    document.getElementById("before").onchange = function() { 
      var date = document.getElementById("before").value;
      document.getElementById('after').max = date;
    };

    // 未読選択時にnewdaysを選択可能にし、初期値3を設定
    document.getElementById("new").onchange = function() {
      if (document.getElementById("new").checked == true) {
        // チェックボックスがONのときの処理
        document.getElementById('newdays').disabled = false;
        document.getElementById('newdays').required = true;
        document.getElementById('newdays').value ="3";
      } else {
          // チェックボックスがOFFのときの処理
        document.getElementById('newdays').disabled = true;
        document.getElementById('newdays').required = false;
      }
    }

  // $('#divisions').eq(0).attr('selected','selected');



});