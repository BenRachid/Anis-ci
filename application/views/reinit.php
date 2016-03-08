<div class="container">
		
		<form class="form-horizontal well" method="post" id="form" action="<?= site_url('doreinit') ?>">
			<fieldset>
	          <legend>Réinitialisation du mot de passe</legend>
			
				<div class="control-group">
					<label for="email"> Email</label>
					<input class="box" type="text" id="email" name="email" />
				</div>
				</br>
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" value="Reset" />
				</div>
				
				<?php if( isset($info)): ?>
					<div class="alert alert-success">
						<?php echo($info) ?>
					</div>
				<?php elseif( isset($error)): ?>
					<div class="alert alert-error">
						<?php echo($error) ?>
					</div>
				<?php endif; ?>
				
			</fieldset>
		</form>
	</div> 