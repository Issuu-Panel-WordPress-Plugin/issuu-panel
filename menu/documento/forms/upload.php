<h1><?php _e('Document', ISSUU_PAINEL_DOMAIN_LANG); ?></h1>
<form action="" method="post" id="document-upload" enctype="multipart/form-data">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="file"><?php _e('File', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td>
					<input type="file" name="file" id="file">
				</td>
			</tr>
			<tr>
				<th><label for="title"><?php _e('Title', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td><input type="text" name="title" id="title" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="name"><?php _e('Name in URL', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td>
					<input type="text" name="name" id="name" class="regular-text code">
					<p class="description">
						<?php _e('Name that is entered in the URL: http://issuu.com/(username)/docs/(name).<br>Use only lowercase letters [a-z], numbers [0-9] and/or other characters [_.-]. Do not use spaces.<br><strong>NOTE:</strong> If you do not enter a value, it will automatically be generated', ISSUU_PAINEL_DOMAIN_LANG); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="description"><?php _e('Description', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td>
					<textarea name="description" id="description" cols="45" rows="6"></textarea>
				</td>
			</tr>
			<tr>
				<th><label for="tags">Tags</label></th>
				<td>
					<textarea name="tags" id="tags" cols="45" rows="6"></textarea>
					<p class="description">
						<?php _e('Use commas to separate tags. Do not use spaces.', ISSUU_PAINEL_DOMAIN_LANG); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th><label><?php _e('Publish date', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td>
					<input type="text" name="pub[day]" id="dia" placeholder="<?php _e('Day', ISSUU_PAINEL_DOMAIN_LANG); ?>" class="small-text"
						maxlength="2"> /
					<input type="text" name="pub[month]" id="mes" placeholder="<?php _e('Month', ISSUU_PAINEL_DOMAIN_LANG); ?>" class="small-text"
						maxlength="2"> /
					<input type="text" name="pub[year]" id="ano" placeholder="<?php _e('Year', ISSUU_PAINEL_DOMAIN_LANG); ?>" class="small-text"
						maxlength="4">
					<p class="description">
						<?php _e('Date of publication of the document.<br><strong>NOTE:</strong> If you do not enter a value, the current date will be used', ISSUU_PAINEL_DOMAIN_LANG); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th><label><?php _e('Folders', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td>
					<?php if (isset($folders['folder']) && !empty($folders['folder'])) : ?>
						<fieldset>
							<?php for ($i = 0; $i < $cnt_f; $i++) : ?>
								<label for="folder<?= $i + 1; ?>">
									<input id="folder<?= $i + 1; ?>" type="checkbox" name="folder[]" value="<?= $folders['folder'][$i]->folderId; ?>">
									<?= $folders['folder'][$i]->name; ?> (<?= $folders['folder'][$i]->items; ?>)
								</label><br>
							<?php endfor; ?>
						</fieldset>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th><label for="commentsAllowed"><?php _e('Allow comments', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td><input type="checkbox" name="commentsAllowed" id="commentsAllowed" value="true"></td>
			</tr>
			<tr>
				<th><label for="downloadable"><?php _e('Allow file download', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td><input type="checkbox" name="downloadable" id="downloadable" value="true"></td>
			</tr>
			<tr>
				<th><label><?php _e('Access', ISSUU_PAINEL_DOMAIN_LANG); ?></label></th>
				<td>
					<fieldset>
						<label for="acesso1">
							<input type="radio" name="access" id="acesso1" value="public">
							<?php _e('Public', ISSUU_PAINEL_DOMAIN_LANG); ?>
						</label><br>
						<label for="acesso2">
							<input type="radio" name="access" id="acesso2" value="private">
							<?php _e('Private', ISSUU_PAINEL_DOMAIN_LANG); ?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th>
					<input type="submit" class="button-primary" value="<?php _e('Save', ISSUU_PAINEL_DOMAIN_LANG); ?>">
					<h3>
						<a href="admin.php?page=issuu-document-admin" style="text-decoration: none;">
							<?php _e('Back', ISSUU_PAINEL_DOMAIN_LANG); ?>
						</a>
					</h3>
				</th>
			</tr>
		</tbody>
	</table>
</form>