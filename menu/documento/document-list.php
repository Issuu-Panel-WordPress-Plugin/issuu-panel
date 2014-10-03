<h1>Lista de documentos</h1>
<form action="" method="post">
	<input type="hidden" name="delete" value="true">
	<a href="admin.php?page=issuu-document-admin&upload" class="buttons-top issuu-other-button" title="">
		Carregar arquivo
	</a>
	<a href="admin.php?page=issuu-document-admin&url_upload" class="buttons-top issuu-other-button" title="">
		Carregar arquivo por URL
	</a>
	<input type="submit" class="buttons-top issuu-submit-button" value="Excluir">
	<div id="document-list">
		<?php if (isset($docs['document']) && !empty($docs['document'])) : ?>
			<?php foreach ($docs['document'] as $doc) : ?>
				<?php if (empty($doc->coverWidth) && empty($doc->coverHeight)) : ?>
					<div id="<?= $doc->orgDocName; ?>" class="document converting">
						<input type="checkbox" name="name[]" class="issuu-checkbox" value="<?= $doc->name; ?>">
						<div class="document-box">
							<div class="loading-issuu"></div>
				<?php else: ?>
					<div class="document complete">
						<input type="checkbox" name="name[]" class="issuu-checkbox" value="<?= $doc->name; ?>">
						<div class="document-box">
							<img src="<?= sprintf($image, $doc->documentId) ?>" alt="">
							<div class="update-document">
								<a href="admin.php?page=issuu-document-admin&update=<?= $doc->orgDocName; ?>">Editar</a>
							</div>
				<?php endif; ?>
					</div>
					<p class="description"><?= $doc->title ?></p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</form>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			var ua = navigator.userAgent.toLowerCase();
			if (ua.indexOf('chrome') <= -1) {
				$('.update-document a').each(function(){
					var p = $(this).parent();
					var width = (p.width() / 2) - 26;
					var height = (p.height() / 2) - 17;

					$(this).css({
						top: height + 'px',
						left: width + 'px'
					});
				});
			}
		});

		var idInt = window.setInterval(atualizaDocs, 5000);

		function atualizaDocs()
		{
			var $con = $('.converting');
			var url = '<?= ISSUU_PAINEL_URL; ?>menu/documento/requests/ajax-docs.php';
			var abspath = '<?= str_replace("\\", "/", ABSPATH); ?>';

			if ($con.length)
			{
				$.ajax(
					url,
					{
						type: 'GET',
						data: {name: $con.attr('id'), abspath: abspath}
					}
				).done(function(data){
					if (data != "stat-fail")
					{
						$con.html(data);
						$con.removeAttr('id');
						$con.addClass('complete').removeClass('converting');
					}
					else
					{
						console.log(data);
					}
				});
			}
			else
			{
				window.clearInterval(idInt);
			}
		}

	})(jQuery);
</script>