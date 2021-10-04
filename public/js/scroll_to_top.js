$(function() {
    // 「TOPに戻る」ボタンがクリックされた時の動きを指定します。
    $("#scroll_to_top").click(function() {
      // ページの一番上までスクロールさせます。
      $('body, html').animate({scrollTop: 0}, 300, 'linear');
    });
    $(window).scroll(function() {
        // 「TOPに戻る」ボタンを取得します。
        var $toTopButton = $('#scroll_to_top');
    
        // 縦にどれだけスクロールしたかを取得します。
        var scrollTop = $(this).scrollTop();
    
        // ウィンドウの縦幅を取得します。
        var windowHeight = $(this).height();
    
        if (scrollTop >= windowHeight) {
          // ウィンドウの縦幅以上にスクロールしていた場合、
          // 「TOPに戻る」ボタンを表示します。
          $toTopButton.show();
        } else {
          // ウィンドウの縦幅以上にスクロールしていない場合、
          // 「TOPに戻る」ボタンを隠します。
          $toTopButton.hide();
        }
      });
  });