<?php
function arpc_SettingsApi(){
	register_setting('arpc_setting_opg', 'arpc_setting_opn');
	add_settings_section('arpc_section_tabbed', __('ARPC Settings', 'arpc-popup-creator'), 'arpc_section_tabbed_render', 'arpc-popup-settings');
	add_settings_field('arpc_choose_temp', __('Choose Pill', 'arpc-popup-creator'), 'arpc_choose_temp_render', 'arpc-popup-settings', 'arpc_section_tabbed', 
		[
			'label_for'				=> 'arpc_choose_temp',
			'class'					=> 'test',
			'arpc_custom_data'		=> 'custom', 
		]
	);
	add_settings_field('arpc_title', __('Choose Title', 'arpc-popup-creator'), 'arpc_title_render', 'arpc-popup-settings', 'arpc_section_tabbed', ['label_for' => 'arpc_title']
	);
}
add_action('admin_init', 'arpc_SettingsApi');

/**
 * Settings Section render
 */
function arpc_section_tabbed_render($args){ ?>
	<p id="<?php echo esc_attr( $args['id'])?>"><?php esc_html_e('Choose the template you like!', 'arpc-popup-creator')?></p>
	<?php
}

function arpc_choose_temp_render($args){
	$options = get_option('arpc_setting_opn'); 
	echo '<pre>';
		  print_r( $options );
	echo '</pre>';
	?>

<div class="arpc_tabbed_wrapper">
<ul class="nav nav-tabs">
	<li class="active"><a href="#choose_template"><?php _e('Choose template', 'arpc-popup-creator'); ?></a></li>
	<li><a href="#overlay"><?php _e('Overlay', 'arpc-popup-creator'); ?></a></li>
	<li><a href="#container"><?php _e('Container', 'arpc-popup-creator'); ?></a></li>
	<li><a href="#title"><?php _e('Title', 'arpc-popup-creator'); ?></a></li>
	<li><a href="#content"><?php _e('Content', 'arpc-popup-creator'); ?></a></li>
	<li><a href="#close"><?php _e('Close Icon', 'arpc-popup-creator'); ?></a></li>
</ul>

<div class="tab-content">
	<div id="choose_template" class="tab-pane active">
		<div>
				<div class="choose-template-wrapper">
				<fieldset>
					<label>
						<input type="radio" name="arpc_setting_opn[<?php echo esc_attr($args['label_for']); ?>]" id="<?php echo esc_attr($args['label_for']) ?>" value="template1" <?php echo isset($options[$args['label_for']]) ? (checked($options[$args['label_for']], 'template1')) : ('') ?>> <?php esc_html_e('Template 1'); ?>
						<img src="<?php echo esc_url(ARPC_ASSETS . '/images/template1.png') ?>" alt="Template 1" />
					</label>
					<label>
						<input type="radio" name="arpc_setting_opn[<?php echo esc_attr($args['label_for']); ?>]" id="<?php echo esc_attr($args['label_for']) ?>" value="template2" <?php echo isset($options[$args['label_for']]) ? (checked($options[$args['label_for']], 'template2')) : ('') ?>> <?php esc_html_e('Template2'); ?>
						<img src="<?php echo esc_url(ARPC_ASSETS . '/images/template2.png') ?>" alt="Template 2" />
					</label>
				</fieldset>
				</div>
		</div>
	</div>
	<div id="overlay" class="tab-pane">
		<div>
			<p>Some sort of content</p>
		</div>
	</div>
	<div id="container" class="tab-pane">
		<p>Hello world</p>
	</div>
	<div id="title" class="tab-pane">
		<p>Some sort of content</p>
	</div>
	<div id="content" class="tab-pane">
		<p>Some sort of content</p>
	</div>
	<div id="close" class="tab-pane">
		<p>Some sort of content</p>
	</div>
</div>
</div>



	<p class="description">
		<?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg'); ?>
	</p>
	<p class="description">
		<?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg'); ?>
	</p>
	<?php
}

function arpc_title_render($args){
	$options = get_option('arpc_setting_opn'); ?>
	<input type="text" name="arpc_setting_opn[<?php echo esc_attr($args['label_for']); ?>]" id="<?php echo esc_attr($args['label_for']); ?>" value="<?php echo esc_attr($options[$args['label_for']]); ?>" />
	<?php
}