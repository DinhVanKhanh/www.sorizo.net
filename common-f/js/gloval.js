
/*---------------------------------------------
	ブラウザサイズの判定
  ---------------------------------------------*/

function getBrowserWidth() {
	return $(window).width();
}

function getBrowserHeight() {
	return $(window).height();
}


/*---------------------------------------------
	ログインボックス
  ---------------------------------------------*/
$(function(){
    $("#serial_key").focus(function() {
        if($(this).val() == $(this).attr('defaultValue'))
            $(this).val('');
            $(this).css('color', '#000').val('');
    }).blur(function() {
        if(jQuery.trim($(this).val()) == "") {
            $(this).val($(this).attr('defaultValue'));
            $(this).css('color', '#999').val($(this).attr('defaultValue'));
        }
    });
});

/*---------------------------------------------
	トップへ戻る
  ---------------------------------------------*/

// ToTop
$(function() {
	// #toTopを消す
	$('#toTop').hide();
	
	// スクロールが十分されたら#toTopを表示、スクロールが戻ったら非表示
	$(window).scroll(function() {
		$('#pos').text($(this).scrollTop());
		if ($(this).scrollTop() > 60) {
			$('#toTop').fadeIn();
		} else {
			$('#toTop').fadeOut();
		}
	});
});

$(document).ready(function() {
    //ハッシュリンクのアンカータグをクリックするとマッチするidを持つ要素にスクロールする
    $('a[href^="#"]').click(function(event) {
 
        var id = $(this).attr("href");
        var offset = 100;
        var target = $(id).offset().top - offset;
        $('html, body').animate({scrollTop:target}, 500, 'easeOutExpo');
        event.preventDefault();
        return false;
    });
});

/*---------------------------------------------
	ボックスのアニメーション
  ---------------------------------------------*/

$(function() {
	$('.box-mm').hover(
		function(){
			$('.boxlink span',this).stop().animate({'opacity':'0'},'easeOutExpo');
		},
		function () {
			$('.boxlink span',this).stop().animate({'opacity':'1'},'easeOutExpo');
		}
	);
});

/*$(function() {
	$('.box-mm').hover(
		function(){
			$('.boxlink span',this).stop().animate({'margin-left':'-300px'},'easeOutExpo');
		},
		function () {
			$('.boxlink span',this).stop().animate({'margin-left':'-0px'},'easeOutExpo');
		}
	);
});*/
