<h1><?php _e('Create new folder', ISSUU_PAINEL_DOMAIN_LANG); ?></h1>
<form action="" id="add-folder" method="post" accept-charset="utf-8">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="folderName"><?php _e("Folder's name", ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td><input type="text" name="folderName" id="folderName" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="folderDescription"><?php _e('Description'); ?></label></th>
				<td><textarea name="folderDescription" id="folderDescription" cols="45" rows="6"></textarea></td>
			</tr>
			<tr>
				<th>
					<input type="submit" value="<?php _e('Save'); ?>" class="button-primary">
					<h3>
						<a href="admin.php?page=issuu-folder-admin" style="text-decoration: none;">
							<?php _e('Back'); ?>
						</a>
					</h3>
				</th>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript" charset="utf-8">
	(function($){
		$('#add-folder').submit(function(){
			if ($('#folderName').val().trim() == "")
			{
				alert('<?php _e("Insert folder\'s name", ISSUU_PAINEL_DOMAIN_LANG); ?>');
				return false;
			}
		});
	})(jQuery);
</script>