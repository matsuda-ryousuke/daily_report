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

              $('#area').append('<option disabled="" selected>選択してください</option>');
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

          $('#client').append('<option disabled="" selected>選択してください</option>');
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

              $('#client').append('<option disabled="" selected>選択してください</option>');
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
              for(var area_group of option_area_group) {
                if(area_group.value === data) {
                  area_group.selected = true;
                } else {
                  area_group.selected = false;
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
              for(var area of option_area) {
                if(area.value === data) {
                  area.selected = true;
                } else {
                  area.selected = false;
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
              for(var area_group of option_area_group) {
                if(area_group.value === data) {
                  area_group.selected = true;
                } else {
                  area_group.selected = false;
                }
              }

        }
        params[4] = function(data){};
        params[5] = function(data){};
        ajaxbase(params);
    
      }




      // $('#divisions').eq(0).attr('selected','selected');



});