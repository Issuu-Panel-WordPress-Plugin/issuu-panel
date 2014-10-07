<h1>Documento</h1>
<form action="" method="post" id="document-upload" enctype="multipart/form-data">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="slurpUrl">URL do arquivo</label></th>
				<td>
					<input type="text" name="slurpUrl" id="slurpUrl" class="regular-text code">
					<p class="description">URL onde o arquivo está localizado</p>
				</td>
			</tr>
			<tr>
				<th><label for="title">Título do documento</label></th>
				<td><input type="text" name="title" id="title" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="name">Nome na URL</label></th>
				<td>
					<input type="text" name="name" id="name" class="regular-text code">
					<p class="description">
						Nome que será inserido na URL:
						http://issuu.com/(nome_do_usuario)/docs/(nome).<br>
						Use somente letras minúsculas [a-z], números [0-9] e/ou outros caracteres[_.-].
						Não use espaços.<br>
						<strong>OBS:</strong> Caso não informe um valor ele será gerado automaticamente
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="description">Descrição</label></th>
				<td>
					<textarea name="description" id="description" cols="45" rows="6"></textarea>
				</td>
			</tr>
			<tr>
				<th><label for="tags">Tags</label></th>
				<td>
					<textarea name="tags" id="tags" cols="45" rows="6"></textarea>
					<p class="description">
						Use vírgulas para separar as tags. Não use espaços.
					</p>
				</td>
			</tr>
			<tr>
				<th><label>Data da publicação</label></th>
				<td>
					<input type="text" name="pub[day]" id="dia" placeholder="Dia" class="small-text" maxlength="2"> /
					<input type="text" name="pub[month]" id="mes" placeholder="Mês" class="small-text" maxlength="2"> /
					<input type="text" name="pub[year]" id="ano" placeholder="Ano" class="small-text" maxlength="4">
					<p class="description">
						Data da publicação do documento.<br>
						<strong>OBS:</strong> Caso não informe um valor a data atual será usada
					</p>
				</td>
			</tr>
			<tr>
				<th><label>Pastas</label></th>
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
				<th><label for="commentsAllowed">Permitir comentários</label></th>
				<td><input type="checkbox" name="commentsAllowed" id="commentsAllowed" value="true"></td>
			</tr>
			<tr>
				<th><label for="downloadable">Permitir download do arquivo</label></th>
				<td><input type="checkbox" name="downloadable" id="downloadable" value="true"></td>
			</tr>
			<tr>
				<th><label>Acesso</label></th>
				<td>
					<fieldset>
						<label for="acesso1"><input type="radio" name="access" id="acesso1" value="public"> Público</label><br>
						<label for="acesso2"><input type="radio" name="access" id="acesso2" value="private"> Privado</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th>
					<input type="submit" class="button-primary" value="Enviar">
					<h3>
						<a href="admin.php?page=issuu-document-admin" style="text-decoration: none;">Voltar</a>
					</h3>
				</th>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript" charset="utf-8">
	(function($){
		function refreshNumbers()
		{
			var dia = $('#dia').val();
			var mes = $('#mes').val();
			var ano = $('#ano').val();

			var maxDia = 31;

			if (mes != '')
			{
				mes = parseInt(mes);

				if (mes < 0)
				{
					mes = 1;
					$('#mes').val(mes);
				}
				else if (mes > 12)
				{
					mes = 12;
					$('#mes').val(mes);
				}

				if (mes != 2)
				{
					if (mes <= 7)
					{
						if (mes % 2 == 0)
						{
							maxDia = 30;
						}
						else
						{
							maxDia = 31;
						}
					}
					else
					{
						if (mes % 2 == 0)
						{
							maxDia = 31;
						}
						else
						{
							maxDia = 30;
						}
					}
				}
				else
				{
					if (ano != '')
					{
						if (ano.length == 4)
						{
							ano = parseInt(ano);

							if (ano % 4 == 0 && ano % 100 != 0 || (ano % 400 == 0))
							{
								maxDia = 29;
							}
							else
							{
								maxDia = 28;
							}
						}
					}
					else
					{
						if (ano.length == 4)
						{
							maxDia = 28;
						}
					}
				}
			}
			else
			{
				maxDia = 31;
			}

			if (dia != '')
			{
				dia = parseInt(dia);

				if (dia < 0)
				{
					$('#dia').val(1);
				}
				else if(dia > maxDia)
				{
					$('#dia').val(maxDia);
				}
			}
		}
		function wholeNumber(e)
		{
			refreshNumbers();

			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
			{
				return false;
			}
		}
		$('.small-text').keypress(wholeNumber);
		$('#document-upload').submit(function(e){
			refreshNumbers();
		});
	})(jQuery);
</script>