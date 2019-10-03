<div class="row">

    <div class="col-md-10 col-md-offset-1">
	<?= $this->draw('admin/menu') ?>
        <h1>Akismet configuration</h1>

    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>admin/akismet/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        To begin using Akismet, <a href="https://akismet.com/signup/?connect=yes&plan=developer" target="_blank">sign up for a developer key</a>, or <a href="https://signup.wordpress.com/signup/" target="_blank">sign up with Akismet</a>.
		    </p>

                </div>
            </div>

            <div class="controls-group">
                <label class="control-label" for="app-id">Akismet / Wordpress Key</label><br>

		<input type="text" id="wordpress_key" placeholder="Your key" class="form-control" name="wordpress_key" value="<?= htmlspecialchars(\Idno\Core\site()->config()->akismet['wordpress_key']) ?>" >



            </div>

	    <div>

                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
	    </div>

	    <?= \Idno\Core\site()->actions()->signForm('/admin/akismet/') ?>
        </form>
    </div>
</div>