$(function(){
    // textareaへの入力時イベント(文字数カウントとカット)
    $('textarea').on('input',function(){charCount($(this),360)});

    // textareaからフォーカスが外れたタイミングのイベント(文字数カウントとカット)
    $('textarea').on('blur',function(){charCount($(this),360)});

    // 「確認する」ボタンクリックイベント(バリデーション実装)
    $('#submit_form').on('click',function(){
        var isValid = 0;
        $('textarea').each(
            function(i,elem){
                isValid += charCount($(elem),360) ? 0 : 1;
            }
        );

        if(isValid > 0 || !$('form').get(0).checkValidity()){
            $('form').get(0).reportValidity();
        } else {
            $('form').submit();
        }
    })

    /**
     * textareaに入力された文字数をカウントし、
     * 最大文字数を超過する分はカット
     * @param {Jquery Object} targetJqObj 
     * @param {Number} maxLength 
     */
    function charCount(targetJqObj,maxLength){
        var inputStr =  targetJqObj.val();
        if(inputStr.length > maxLength){
            targetJqObj.val(inputStr.substr(0, maxLength));
            targetJqObj[0].setCustomValidity("文字数は改行含め" + maxLength + "文字迄です。");
            setTimeout(function(){
                targetJqObj[0].setCustomValidity("");
            },5000);
            return false;
        } else {
            return true;
        }
    }

    /**
     * textarea内の文字数をカウント、
     * 業務内容欄
     * 
     */
    
    $('#visitContents').on('input', function(){
        //文字数を取得
        var cnt = $(this).val().length;
        //現在の文字数を表示
        $('.now_cnt').text(cnt);
        if(cnt >= 0 && 329 >= cnt){
            //1文字以上かつ330文字未満の場合は黒字
            $('.cnt_area').removeClass('cnt_danger');
        }else{
            //330文字を超える場合は赤字
            $('.cnt_area').addClass('cnt_danger');
        }
        });
    
    //リロード時に初期文字列が入っていた時の対策
    $('#visitContents').trigger('input');
    

    /**
     * textarea内の文字数をカウント、
     * 次の展開欄
     * 
     */
    
        $('#nextStep').on('input', function(){
        //文字数を取得
        var cnt = $(this).val().length;
        //現在の文字数を表示
        $('.now_cnt2').text(cnt);
        if(cnt >= 0 && 329 >= cnt){
            //1文字以上かつ330文字未満の場合は黒字
            $('.cnt_area2').removeClass('cnt_danger');
        }else{
            //330文字を超える場合は赤字
            $('.cnt_area2').addClass('cnt_danger');
        }
        });
        
        //リロード時に初期文字列が入っていた時の対策
        $('#nextStep').trigger('input');
        

    
});