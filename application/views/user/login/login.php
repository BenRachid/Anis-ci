<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<?php if (validation_errors()) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= validation_errors() ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if (isset($error)) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= $error ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-horizontal wel">
			<div class="page-header">
				<h1>Login</h1>
			</div>
			
			<?= form_open() ?>
			<fieldset>
				<div class="control-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Votre username">
				</div>
				<div class="control-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Votre password">
				</div>
				
				</br>
				<div class="form-action">
					<input type="submit" class="btn btn-default" value="Login">
					<a href="<?= site_url('forgot') ?>">Mot de passe oublié</a>
				</div>
			</fieldset>
			</form>
		</div>
	</div><!-- .row -->
</div><!-- .container -->