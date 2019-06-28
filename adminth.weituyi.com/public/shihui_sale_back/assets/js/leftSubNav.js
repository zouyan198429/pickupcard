$('.J-navLeft > li').on('click', function(e) {			
	$(this).addClass('active').siblings().removeClass('active');
});

$('.submenu li').click(function() {	
	var $parentsUl = $(this).parents('.J-navLeft');	
	$parentsUl.children('li').each(function(){		
		$(this).find('.submenu li').removeClass('active');
	})
	$(this).addClass('active').siblings().removeClass('active');
});
$(function(){
    $(".submenu li>a").click(function(){
        $("frame[name='rightFrame']", parent.document).attr("src",$(this).attr("href")); 
        $(this).parent().click();
        return false;           
    });
});