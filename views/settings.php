<div class="legalmonster-plugin">
<div class="wrap">
    <h2><?php echo $this->plugin->displayName; ?> &raquo; <?php esc_html_e( 'Settings', 'legalmonster' ); ?></h2>

    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<!-- Content -->
    		<div id="post-body-content">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	                <div class="postbox">
	                    <div class="inside">
	                    <h1>Step 1: Sign up for Legal Monster</h1>
	                    	<p>To get started, you must create your Legal Monster account and follow the steps to create your cookie pop-up!</p>

	                    	<form action="https://legalmonster.com/signup">
							    <input type="submit" value="Sign up for free" class="btn btn-success"/>
							</form>
<br>
	                    	<h1>Step 2: Public key</h1>
	                    	<p>
	                    		To install your widget, you'll need your cookie widget's public key. 
	                    		Go to the <a href="https://app.legalmonster.com">Legal Monster dashboard</a> and go to "Widgets" via the menu. Copy the string code shown in pink under public key. In the example image, the public key is <code>bDEsDsX76teFhHQZYDcWx7Hm</code>. Your widget public key <b>is different</b>. 
	                    	</p>
	                    	<img src="<?php echo plugin_dir_url( __DIR__ ).'views/img/install.png';?>" class="w-75">

	                    	<h1>Step 3: Paste public key</h1>
	                    	<p>Paste your public key in the below textfield and press save. Voila! </p>

		                    <form action="options-general.php?page=<?php echo esc_html($this->plugin->name); ?>" method="post">
								<p>
									<label for="lm_insert_footer"><strong><?php esc_html_e( 'Widget Public key', 'legalmonster' ); ?></strong></label>
									<input name="lm_insert_footer" id="lm_insert_footer" class="widefat" rows="8" style="font-family:Courier New;" value="<?php echo esc_html($this->settings['lm_insert_footer']); ?>"></input>
		                    	</p>
		                    	<?php wp_nonce_field( $this->plugin->name, $this->plugin->name . '_nonce' ); ?>
									<input name="submit" type="submit" name="Submit" class="btn btn-success" value="<?php esc_attr_e( 'Save', 'legalmonster' ); ?>" />
						    </form>
	                    </div>
	                </div>
	                <!-- /postbox -->
				</div>
				<!-- /normal-sortables -->
    		</div>
    		<!-- /post-body-content -->
			<!-- Sidebar -->
    		<div id="postbox-container-1" class="postbox-container">
    			<?php require_once( $this->plugin->folder . '/views/sidebar.php' ); ?>
    		</div>
    		<!-- /postbox-container -->
    	</div>
	</div>
</div>
</div>