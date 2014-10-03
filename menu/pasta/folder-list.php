<h1>Lista de pastas</h1>
<div class="issuu-folder-content">
	<?php foreach ($folders_documents as $key => $value) : ?>
		<div class="issuu-folder">
			<a href="admin.php?page=issuu-folder-admin&folder">
				<?php for ($i = 0; $i < 3; $i++) : ?>
					<?php if (isset($value['documentsId'][$i])) : ?>
						<div class="folder-item">
							<img src="<?= sprintf($image, $value['documentsId'][$i]); ?>">
						</div><!-- FIM folder-item -->
					<?php else: ?>
						<div class="folder-item"></div><!-- FIM folder-item -->
					<?php endif; ?>
				<?php endfor; ?>
				<p>
					<span><?= $value['name']; ?></span>
				</p>
			</a>
		</div><!-- FIM issuu-folder -->
	<?php endforeach; ?>
	<div class="issuu-folder">
		<a href="admin.php?page=issuu-folder-admin&add">
			<div class="folder-item"></div><!-- FIM folder-item -->
			<div class="folder-item"></div><!-- FIM folder-item -->
			<div class="folder-item"></div><!-- FIM folder-item -->
			<p>
				<span class="add-stack" title="Criar nova pasta"></span><!-- FIM add-stack -->
			</p>
		</a>
	</div><!-- FIM issuu-folder -->
</div><!-- FIM issuu-folder-content -->