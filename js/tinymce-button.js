(function() {
	tinymce.create('tinymce.plugins.IssuuPainel', {
		init : function(ed, url) {
			ed.addButton('issuupainel', {
				title : 'issuupainel.youtube',
				image : url+'/../images/issuu-painel-tinymce-button.png',
				onclick : function() {
					idPattern = /(?:(?:[^v]+)+v.)?([^&=]{11})(?=&|$)/;
					var vidId = prompt("YouTube Video", "Enter the id or url for your video");
					var m = idPattern.exec(vidId);
					if (m != null && m != 'undefined')
						ed.execCommand('mceInsertContent', false, '[youtube id="'+m[1]+'"]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
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