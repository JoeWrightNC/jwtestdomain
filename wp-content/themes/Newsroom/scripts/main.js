var $ = jQuery;
var nc = {};
var windowWidth = $(window).outerWidth();
var windowHeight = $(window).height();

//Set to false if want to hide special cats
var showSpecialCats = false;
//Add categories separated by commas
var categories = ["Commentary"];


nc.hideCats = function() {
	
	var currentCat, str;
	
	$(".td-post-category").each(function(index, element) {
        
		currentCat = $(this).text();
		str = currentCat.replace(/\s+/g, '-').toLowerCase();
		
		if ($.inArray(currentCat, categories) !== -1) {
			$(this).attr('style', 'display: inline-block !important');
			$(this).addClass("tag-"+str);
		}
    });
	
	$(categories).each(function(index, element) {
		
		str = element.replace(/\s+/g, '-').toLowerCase();
		
		if ($('.category-'+str).length) {
			$('.category-'+str+' .td-post-category').attr('style', 'display: none !important');
		}
	});
}



nc.addTargetBlank = function() {
	
	if ($(".td-social-icon-wrap").length) {
			$(".td-social-icon-wrap a").attr("target","_blank");
	}
	if ($(".jetpack-social-widget-item").length) {
			$(".jetpack-social-widget-item a").attr("target","_blank");
	}
	
}



$(document).ready(function() {

	if ($(".td-post-category").length && showSpecialCats == true) {
		nc.hideCats();
	}
	nc.addTargetBlank();

});


$(window).load(function(){


});	


$( window ).resize(function() {

});

