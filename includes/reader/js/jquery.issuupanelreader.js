var $nav, $ul, $prev, $next, $iframe, $tools;

(function($){
	$.fn.issuuPanelReader = function(options){
		var defaults = {
			prevNavigation : "#ip-reader-prev",
			nextNavigation : "#ip-reader-next",
			zoomMore : '.ip-zoom-more',
			zoomMinus : '.ip-zoom-minus',
			closeReader : '.ip-close-reader'
		};
		$.issuuPanelReaderIframe = $(this).contents();
		$.issuuPanelReader = {
			zoom : 100
		};

		options = $.extend({}, defaults, options);

		$(options.prevNavigation).click(function(e){
			e.preventDefault();
			var page = $.issuuPanelReaderIframe.find('.ip-doc-active');
			var pageNumber = page.find('img').data('ip-reader-page');

			if (pageNumber > 1)
			{
				pageNumber--;
				$.issuuPanelReader.zoom = 100;
				page.removeClass('ip-doc-active');
				$.issuuPanelReaderIframe
					.find('[data-ip-reader-page="' + pageNumber + '"]').parent().addClass('ip-doc-active');
				$.issuuPanelReaderIframe.find('body').css({zoom : $.issuuPanelReader.zoom + '%'});
			}
		});

		$(options.nextNavigation).click(function(e){
			e.preventDefault();
			var countPages = parseInt($.issuuPanelReaderIframe.find('#issuu-panel-document').data('ip-reader-pages'));
			var page = $.issuuPanelReaderIframe.find('.ip-doc-active');
			var pageNumber = page.find('img').data('ip-reader-page');

			if (pageNumber < countPages)
			{
				pageNumber++;
				$.issuuPanelReader.zoom = 100;
				page.removeClass('ip-doc-active');
				$.issuuPanelReaderIframe
					.find('[data-ip-reader-page="' + pageNumber + '"]').parent().addClass('ip-doc-active');
				$.issuuPanelReaderIframe.find('body').css({zoom : $.issuuPanelReader.zoom + '%'});
			}
		});

		$(options.zoomMore).click(function(e){
			e.preventDefault();

			if ($.issuuPanelReader.zoom < 500)
			{
				if ($.issuuPanelReader.zoom < 200)
				{
					$.issuuPanelReader.zoom += 10;
				}
				else
				{
					$.issuuPanelReader.zoom += 20;
				}
			}

			$.issuuPanelReaderIframe.find('#issuu-panel-document').css({zoom : $.issuuPanelReader.zoom + '%'});
		});

		$(options.zoomMinus).click(function(e){
			e.preventDefault();

			if ($.issuuPanelReader.zoom > 100)
			{
				if ($.issuuPanelReader.zoom > 200)
				{
					$.issuuPanelReader.zoom -= 20;
				}
				else
				{
					$.issuuPanelReader.zoom -= 10;
				}
			}

			$.issuuPanelReaderIframe.find('#issuu-panel-document').css({zoom : $.issuuPanelReader.zoom + '%'});
		});

		$(options.closeReader).click(function(e){
			$('#issuu-panel-reader').fadeOut(500, function(){
				$(this).remove();
				$iframe = null;
			});
		});

		return this;
	}

	$(document).ready(function(){
		$('[data-toggle="issuu-panel-reader"]').click(function(e){
			e.preventDefault();
			$('<div>',{
				id : 'issuu-panel-reader'
			}).appendTo('body');
			$('<div>', {
				id: 'botao-issuu'
			}).appendTo('#issuu-panel-reader');
			$(document).keyup(function(e){
				if (e.keyCode == 27)
				{
					$('#issuu-panel-reader').fadeOut(500, function(){
						$(this).remove();
						$iframe = null;
					});
				}
			});
			$('#issuu-panel-reader').hide().fadeIn(500);
			$('#botao-issuu').delay(2000).fadeOut(500, function(){
				$(this).remove();
				$('<iframe>', {
					id : 'ip-iframe-reader',
					src : 'reader.html'
				})
				.appendTo('#issuu-panel-reader')
				.load(function(){
					$(this).issuuPanelReader();
					$iframe = $(this).contents();
				});
			});
			$tools = $('<div>', {
				id : 'issuu-panel-reader-tools'
			}).appendTo('#issuu-panel-reader');
			$('<div>', {
				'class' : 'issuu-panel-reader-tools ip-zoom-more'
			}).appendTo($tools);
			$('<div>', {
				'class' : 'issuu-panel-reader-tools ip-zoom-minus'
			}).appendTo($tools);
			$('<div>', {
				'class' : 'issuu-panel-reader-tools ip-close-reader'
			}).appendTo($tools);
			$nav = $('<nav>').appendTo('#issuu-panel-reader');
			$ul = $('<ul>').appendTo($nav);
			$prev = $('<li>').appendTo($ul);
			$next = $('<li>').appendTo($ul);
			$('<a>', {
				href: '#',
				id : 'ip-reader-prev',
				'class' : 'ip-reader-navigation'
			}).appendTo($prev);
			$('<a>', {
				href: '#',
				id : 'ip-reader-next',
				'class' : 'ip-reader-navigation'
			}).appendTo($next);
		});
	});
})(jQuery);