(function($){
	$('.link-issuu-document').click(function(e){
		console.log(e);
		var script = '<div data-url="' +
			$(this).attr('href') + '" style="width: 100%; height: 323px;" class="issuuembed"></div>';
		script += '<script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>';

		$('#issuu-iframe').html(script);
		e.preventDefault();
	});
})(jQuery);