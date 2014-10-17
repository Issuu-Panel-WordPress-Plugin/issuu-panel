(function(){
	tinymce.create('tinymce.plugins.IssuuPainel', {
		init : function(ed, url) {
			var popupIssuu = url + '/tinymce/ola-mundo.php';

			ed.addCommand('IssuuPainelCommand', function(){
				ed.windowManager.open({
					url: popupIssuu,
					width: 600,
					height: 500,
					inline: 1
				});
			});

			ed.addButton('issuupainel', {
				title : 'Issuu Painel Shortcode',
				image : url+'/../images/issuu-painel-tinymce-button.png',
				cmd: 'IssuuPainelCommand'
			});
		},
		createControl : function(n, cm){
			return null;
		},
		getInfo : function(){
			return {
				longname : "Issuu Painel",
				author : 'Pedro Marcelo',
				authorurl : 'https://github.com/pedromarcelojava/',
				infourl : 'https://github.com/pedromarcelojava/Issuu-Painel/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('issuupainel', tinymce.plugins.IssuuPainel);
})();