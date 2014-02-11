<?php

/*
Written by Chris Jean for iThemes.com
Version 1.1.7

Version History
	See history.txt
*/


if ( ! class_exists( 'iThemesFeaturedImages2' ) ) {
	class iThemesFeaturedImages2 {
		var $_var = 'ithemes_featured_images';
		var $_name = 'Featured Images';
		var $_page = 'ithemes-featured-images';
		
		var $_defaults = array(
			'width'								=> '100',
			'height'							=> '100',
			'sleep'								=> '2',
			'fade'								=> '1',
			'image_ids'							=> array(),
			'fade_sort'							=> 'ordered',
			'enable_fade'						=> '1',
			'link'								=> '',
			'open_new_window'					=> '',
			'enable_overlay'					=> '1',
			'overlay_text_alignment'			=> 'center',
			'overlay_text_vertical_position'	=> 'middle',
			'overlay_text_padding'				=> '10',
			'overlay_header_text'				=> '',
			'overlay_header_size'				=> '36',
			'overlay_header_color'				=> '#FFFFFF',
			'overlay_subheader_text'			=> '',
			'overlay_subheader_size'			=> '18',
			'overlay_subheader_color'			=> '#FFFFFF',
			
			'variable_width'					=> false,
			'variable_height'					=> true,
			'force_disable_overlay'				=> false,
		);
		
		var $_options = array();
		
		var $_class = '';
		var $_initialized = false;
		
		var $_usedInputs = array();
		var $_selectedVars = array();
		var $_pluginPath = '';
		var $_pluginRelativePath = '';
		var $_pluginURL = '';
		var $_pageRef = '';
		
		
		function iThemesFeaturedImages2() {
			$this->_defaults['link'] = get_option( 'home' );
			$this->_defaults['overlay_header_text'] = get_bloginfo( 'name' );
			$this->_defaults['overlay_subheader_text'] = get_bloginfo( 'description' );
			
			$this->_defaults = apply_filters( 'it_featured_images_options', $this->_defaults );
			
			
			$this->_setVars();
			
			//add_action( 'ithemes_set_defaults', array( &$this, 'setDefaults' ) );
			//add_action( 'ithemes_init', array( &$this, 'init' ) );
			
			// Only run admin backend if on admin page for this plugin or non-admin page below...
			if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->_page ) ) {
				add_action( 'admin_init', array( &$this, 'init' ) );
			}
			add_action( 'template_redirect', array( &$this, 'init' ) ); // non-admin page.
			
			add_action( 'admin_menu', array( &$this, 'addPages' ) );
			add_action( 'ithemes_featured_images_fade_images', array( &$this, 'fadeImages' ) );
		}
		
		function init() {
			add_action( 'ithemes_set_defaults', array( &$this, 'setDefaults' ) );
			$this->_load();
		}
		
		function addPages() {
			global $wp_theme_name, $wp_theme_page_name;
			
			if ( ! empty( $wp_theme_page_name ) )
				$this->_pageRef = add_submenu_page( $wp_theme_page_name, $this->_name, $this->_name, 'edit_themes', $this->_page, array( &$this, 'index' ) );
			else
				$this->_pageRef = add_theme_page( $wp_theme_name . ' ' . $this->_name, $wp_theme_name . ' ' . $this->_name, 'edit_themes', $this->_page, array( &$this, 'index' ) );
			
			add_action( 'admin_print_scripts-' . $this->_pageRef, array( $this, 'addAdminScripts' ) );
			add_action( 'admin_print_styles-' . $this->_pageRef, array( $this, 'addAdminStyles' ) );
		}
		
		function addAdminStyles() {
			wp_enqueue_style( 'thickbox' );
			
			wp_enqueue_style( $this->_var . '-featured-images', $this->_pluginURL . '/css/admin-style.css' );
		}
		
		function addAdminScripts() {
			global $wp_scripts;
			
			
			$queue = array();
			
			foreach ( (array) $wp_scripts->queue as $item )
				if ( ! in_array( $item, array( 'page', 'editor', 'editor_functions', 'tiny_mce', 'media-upload', 'post' ) ) )
					$queue[] = $item;
			
			$wp_scripts->queue = $queue;
			
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'thickbox' );
			
			wp_enqueue_script( $this->_var . '-prototype', $this->_pluginURL . '/js/prototype.js' );
			wp_enqueue_script( $this->_var . '-color-methods', $this->_pluginURL . '/js/colorpicker/ColorMethods.js' );
			wp_enqueue_script( $this->_var . '-color-value-picker', $this->_pluginURL . '/js/colorpicker/ColorValuePicker.js' );
			wp_enqueue_script( $this->_var . '-slider', $this->_pluginURL . '/js/colorpicker/Slider.js' );
			wp_enqueue_script( $this->_var . '-color-picker', $this->_pluginURL . '/js/colorpicker/ColorPicker.js' );
			
			wp_enqueue_script( $this->_var . '-toolkit', $this->_pluginURL . '/js/javascript-toolbox-toolkit.js' );
			wp_enqueue_script( $this->_var . '-featured-images', $this->_pluginURL . '/js/admin-featured-images.js' );
		}
		
		function _setVars() {
			$this->_class = get_class( $this );
			
			$this->_pluginPath = dirname( __FILE__ );
			$this->_pluginRelativePath = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_pluginPath ) ), '\\\/' );
			$this->_pluginURL = get_option( 'siteurl' ) . '/' . $this->_pluginRelativePath;
			
			$this->_selfLink = array_shift( explode( '?', $_SERVER['REQUEST_URI'] ) ) . '?page=' . $this->_page;
		}
		
		
		// Options Storage ////////////////////////////
		
		function setDefaults() {
			global $ithemes_theme_options;
			
			if ( is_array( $ithemes_theme_options->_options[$this->_var] ) && ! isset( $ithemes_theme_options->_options[$this->_var]['enable_overlay'] ) )
				$this->_defaults['enable_overlay'] = '';
			
			$ithemes_theme_options->force_defaults[$this->_var] = $this->_defaults;
		}
		
		function _save() {
			do_action( 'ithemes_save', $this->_var, $this->_options );
			
			return true;
		}
		
		function _load() {
			global $ithemes_theme_options;
			
			
			$this->_options = $ithemes_theme_options->_options[$this->_var];
			
			$this->_options['sleep'] = floatval( $this->_options['sleep'] );
			$this->_options['fade'] = floatval( $this->_options['fade'] );
			
			if ( $this->_options['sleep'] <= 0 )
				$this->_options['sleep'] = $this->_defaults['sleep'];
			if ( $this->_options['fade'] <= 0 )
				$this->_options['fade'] = $this->_defaults['fade'];
			if ( empty( $this->_options['fade_sort'] ) )
				$this->_options['fade_sort'] = 'ordered';
			
			foreach ( array( 'width', 'height', 'sleep', 'fade' ) as $option ) {
				if ( ! is_numeric( $this->_defaults[$option] ) )
					$this->_options[$option] = $GLOBALS[$this->_defaults[$option]];
				else if ( ( empty( $this->_options[$option] ) ) && ( '0' !== $this->_options[$option] ) )
					$this->_options[$option] = $this->_defaults[$option];
			}
			
			
			if ( empty( $this->_options['image_ids'] ) )
				$this->_initializeImages();
			else if ( ! is_array( reset( $this->_options['image_ids'] ) ) ) {
				$entries = array();
				
				$order = 1;
				
				foreach ( (array) $this->_options['image_ids'] as $id ) {
					$entry = array();
					$entry['attachment_id'] = $id;
					$entry['url'] = '';
					$entry['order'] = $order;
					
					$entries[] = $entry;
					
					$order++;
				}
				
				$this->_options['image_ids'] = $entries;
			}
			
			if ( ( false === $this->_defaults['variable_height'] ) && is_numeric( $this->_defaults['height'] ) )
				$this->_options['height'] = $this->_defaults['height'];
			if ( ( false === $this->_defaults['variable_width'] ) && is_numeric( $this->_defaults['width'] ) )
				$this->_options['width'] = $this->_defaults['width'];
			
			$this->_save();
		}
		
		
		// Pages //////////////////////////////////////
		
		function index() {
			if ( function_exists( 'current_user_can' ) && ! current_user_can( 'edit_themes' ) )
				die( __( 'Cheatin uh?' ) );
			
			
			$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';
			
			if ( 'save' === $action )
				$this->_saveForm();
			else if ( 'save_image' === $action )
				$this->_saveImage();
			else if ( ! empty( $_POST['save_entry_order'] ) )
				$this->_saveOrder();
			else if ( 'upload' === $action )
				$this->_uploadImage();
			else if ( ! empty( $_REQUEST['delete_images'] ) )
				$this->_deleteImages();
			
			$this->_showForm();
		}
		
		function _saveForm() {
			check_admin_referer( $this->_var . '-nonce' );
			
			
			foreach ( (array) explode( ',', $_POST['used-inputs'] ) as $name ) {
				$is_array = ( preg_match( '/\[\]$/', $name ) ) ? true : false;
				
				$name = str_replace( '[]', '', $name );
				$var_name = preg_replace( '/^' . $this->_var . '-/', '', $name );
				
				if ( $is_array && empty( $_POST[$name] ) )
					$_POST[$name] = array();
				
				if ( isset( $_POST[$name] ) && ! is_array( $_POST[$name] ) )
					$this->_options[$var_name] = stripslashes( $_POST[$name] );
				else if ( isset( $_POST[$name] ) )
					$this->_options[$var_name] = $_POST[$name];
				else
					$this->_options[$var_name] = '';
			}
			
			
			$errorCount = 0;
			
			if ( ( $this->_options['sleep'] != floatval( $this->_options['sleep'] ) ) || ( floatval( $this->_options['sleep'] ) <= 0 ) )
				$errorCount++;
			if ( ( $this->_options['fade'] != floatval( $this->_options['fade'] ) ) || ( floatval( $this->_options['fade'] ) <= 0 ) )
				$errorCount++;
			if ( ( $this->_options['height'] != intval( $this->_options['height'] ) ) || ( intval( $this->_options['height'] ) < 0 ) )
				$errorCount++;
			
			if ( $errorCount < 1 ) {
				$this->_options['sleep'] = floatval( $this->_options['sleep'] );
				$this->_options['fade'] = floatval( $this->_options['fade'] );
				
				if ( $this->_options['sleep'] <= 0 )
					$this->_options['sleep'] = $this->_defaults['sleep'];
				if ( $this->_options['fade'] <= 0 )
					$this->_options['fade'] = $this->_defaults['fade'];
				if ( empty( $this->_options['fade_sort'] ) )
					$this->_options['fade_sort'] = 'ordered';
				
				foreach ( array( 'width', 'height', 'sleep', 'fade' ) as $option )
					if ( ! is_numeric( $this->_defaults[$option] ) )
						$this->_options[$option] = $GLOBALS[$this->_defaults[$option]];
					elseif ( ( empty( $this->_options[$option] ) ) && ( '0' !== $this->_options[$option] ) )
						$this->_options[$option] = $this->_defaults[$option];
				
				if ( $this->_save() )
					$this->_showStatusMessage( __( 'Settings updated', $this->_var ) );
				else
					$this->_showErrorMessage( __( 'Error while updating settings', $this->_var ) );
			}
			else {
				$this->_showErrorMessage( __( 'The fade options timing values must be numeric values greater than 0.', $this->_var ) );
				
				$this->_showErrorMessage( __ngettext( 'Please fix the input marked in red below.', 'Please fix the inputs marked in red below.', $errorCount ) );
			}
		}
		
		function _saveOrder() {
			check_admin_referer( $this->_var . '-nonce' );
			
			
			foreach ( (array) $_POST as $var => $value ) {
				if ( preg_match( '/^' . $this->_var . '-entry-order-(\d+)$/', $var, $matches ) ) {
					$entry_id = $matches[1];
					
					if ( ! empty( $this->_options['image_ids'][$entry_id] ) && is_array( $this->_options['image_ids'][$entry_id] ) )
						$this->_options['image_ids'][$entry_id]['order'] = $value;
				}
			}
			
			$this->_save();
			
			
			$this->_showStatusMessage( 'Successfully updated the entry order' );
		}
		
		function _saveImage() {
			check_admin_referer( $this->_var . '-nonce' );
			
			if ( isset( $_POST[$this->_var . '-attachment_id'] ) )
				$attachment_id = $_POST[$this->_var . '-attachment_id'];
			
			if ( is_array( $_FILES['image_upload'] ) && ( 0 === $_FILES['image_upload']['error'] ) ) {
				require_once( TEMPLATEPATH . '/lib/file-utility/file-utility.php' );
				
				$file = iThemesFileUtility::uploadFile( 'image_upload' );
				
				if ( is_wp_error( $file ) )
					$this->_errors[] = 'Unable to save uploaded image. Ensure that the web server has permissions to write to the uploads folder';
				else
					$attachment_id = $file['id'];
			}
			else if ( ! isset( $_POST['image_id'] ) )
				$this->_errors[] = 'You must use the browse button to select an image to upload.';
			else if ( empty( $attachment_id ) ) {
				$this->_errors[] = 'An unexpected error occurred. Unable to find the needed image attachment.';
				$this->_errors[] = 'Please click on the Featured Images menu link and try again.';
			}
			
			if ( ! empty( $this->_errors ) ) {
				$this->_attachment_id = $attachment_id;
				return;
			}
			
			if ( isset( $_POST[$this->_var . '-order'] ) )
				$order = $_POST[$this->_var . '-order'];
			else {
				$order = 0;
				
				foreach ( (array) $this->_options['image_ids'] as $entry )
					if ( $entry['order'] > $order )
						$order = $entry['order'];
				
				$order++;
			}
			
			$entry = array();
			$entry['attachment_id'] = $attachment_id;
			$entry['url'] = $_POST[$this->_var . '-url'];
			$entry['order'] = $order;
			
			if ( isset( $_POST['image_id'] ) && is_array( $this->_options['image_ids'][$_POST['image_id']] ) )
				$this->_options['image_ids'][$_POST['image_id']] = $entry;
			else
				$this->_options['image_ids'][] = $entry;
			
			$this->_save();
			
			if ( isset( $_POST['image_id'] ) ) {
				$this->_showStatusMessage( 'Updated Image Settings' );
				unset( $_POST['image_id'] );
				unset( $_REQUEST['image_id'] );
			}
			else
				$this->_showStatusMessage( 'Added New Image' );
			
			unset( $_POST['action'] );
			unset( $_REQUEST['action'] );
		}
		
		function _deleteImages() {
			check_admin_referer( $this->_var . '-nonce' );
			
			require_once( TEMPLATEPATH . '/lib/file-utility/file-utility.php' );
			
			$names = array();
			
			if ( ! empty( $_POST['entries'] ) && is_array( $_POST['entries'] ) ) {
				foreach ( (array) $_POST['entries'] as $id ) {
					$file_name = basename( get_attached_file( $this->_options['image_ids'][$id]['attachment_id'] ) );
					$names[] = $file_name;
					
					iThemesFileUtility::delete_file_attachment( $this->_options['image_ids'][$id]['attachment_id'] );
					
					unset( $this->_options['image_ids'][$id] );
				}
			}
			
			natcasesort( $names );
			
			if ( ! empty( $names ) ) {
				$this->_save();
				
				$this->_showStatusMessage( 'Successfully deleted the following ' . __ngettext( 'image', 'images', count( $names ) ) . ': ' . implode( ', ', $names ) );
			}
			else
				$this->_showErrorMessage( 'No entries were selected for deletion' );
		}
		
		function _showForm() {
			if ( isset( $this->_addedAnimatedFile ) && ( true === $this->_addedAnimatedFile ) )
				$this->_showStatusMessage( 'An animated image was just uploaded. It may take a moment for this screen to fully render as the animation is resized.' );
			
			
			$ratio = $this->_options['width'] / $this->_options['height'];
			
			$thumb_height = $thumb_width = 100;
			
			if ( $ratio > 1 )
				$thumb_height = intval( 100 / ( $this->_options['width'] ) * $this->_options['height'] );
			else
				$thumb_width = intval( 100 / ( $this->_options['height'] ) * $this->_options['width'] );
			
			
			require_once( TEMPLATEPATH . '/lib/file-utility/file-utility.php' );
			
?>
	<?php if ( ! isset( $this->_errors ) && ! isset( $_REQUEST['image_id'] ) && ( ! isset( $_REQUEST['action'] ) || ( 'save_image' !== $_REQUEST['action'] ) ) ) : ?>
		<div class="wrap">
			<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink; ?>">
				<?php wp_nonce_field( $this->_var . '-nonce' ); ?>
				
				<h2>Featured Images</h2>
				
				<?php if ( count( $this->_options['image_ids'] ) > 0 ) : ?>
					<div class="tablenav">
						<div class="alignleft actions">
							<?php $this->_addSubmit( 'delete_images', array( 'value' => 'Delete', 'class' => 'button-secondary delete' ) ); ?>
							<?php $this->_addSubmit( 'save_entry_order', array( 'value' => 'Save Order', 'class' => 'button-secondary' ) ); ?>
						</div>
						
						<br class="clear" />
					</div>
					
					<br class="clear" />
					
					<table class="widefat">
						<thead>
							<tr class="thead">
								<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
								<th>Image</th>
								<th>File Name</th>
								<th>Link</th>
								<th class="num">Reorder</th>
							</tr>
						</thead>
						<tfoot>
							<tr class="thead">
								<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
								<th>Image</th>
								<th>File Name</th>
								<th>Link</th>
								<th class="num">Reorder</th>
							</tr>
						</tfoot>
						<tbody>
							<?php
								$class = 'alternate';
								$order = 1;
								
								uksort( $this->_options['image_ids'], array( &$this, '_orderedSort' ) );
							?>
							<?php foreach ( (array) $this->_options['image_ids'] as $id => $entry ) : ?>
								<?php
									flush();
									
									$file_name = basename( get_attached_file( $entry['attachment_id'] ) );
									
									$thumb = iThemesFileUtility::resize_image( $entry['attachment_id'], $thumb_width, $thumb_height, true );
									
									$this->_options['entry-order-' . $id] = $entry['order'];
								?>
								<tr class="entry-row <?php echo $class; ?>" id="entry-<?php echo $id; ?>">
									<th scope="row" class="check-column">
										<input type="checkbox" name="entries[]" class="entries" value="<?php echo $id; ?>" />
									</th>
									<td>
										<?php if ( ! is_wp_error( $thumb ) ) : ?>
											<img src="<?php echo $thumb['url']; ?>" alt="<?php echo $thumb['file']; ?>" style="float:left; margin-right:10px;" />
										<?php else : ?>
											Thumbnail generation error: <?php echo $thumb->get_error_message(); ?>
										<?php endif; ?>
										<div class="row-actions" style="margin:0; padding:0;">
											<span class="edit"><a href="<?php echo $this->_selfLink; ?>&image_id=<?php echo $id; ?>">Edit Image Settings</a></span>
										</div>
									</td>
									<td>
										<?php echo $file_name; ?>
									</td>
									<td>
										<a href="<?php echo $entry['url']; ?>" target="_blank" title="<?php echo $entry['url']; ?>"><?php echo $entry['url']; ?></a>
									</td>
									<td class="num">
										<div style="margin-bottom:5px;" class="entry-up"><img src="<?php echo $this->_pluginURL; ?>/images/blue-up.png" alt="move up" /></div>
										<div class="entry-down"><img src="<?php echo $this->_pluginURL; ?>/images/blue-down.png" alt="move down" /></div>
										<?php $this->_addHidden( 'entry-order-' . $id, array( 'class' => 'entry-order' ) ); ?>
									</td>
								</tr>
								<?php $class = ( $class === '' ) ? 'alternate' : ''; ?>
								<?php $order++; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
					
					<div class="tablenav">
						<div class="alignleft actions">
							<?php $this->_addSubmit( 'delete_images', array( 'value' => 'Delete', 'class' => 'button-secondary delete' ) ); ?>
							<?php $this->_addSubmit( 'save_entry_order', array( 'value' => 'Save Order', 'class' => 'button-secondary' ) ); ?>
						</div>
						
						<br class="clear" />
					</div>
				<?php endif; ?>
			</form>
		</div>
		
		<br class="clear" />
	<?php endif; ?>
	
	<?php if ( ! isset( $this->_errors ) || isset( $_REQUEST['image_id'] ) || ( isset( $_REQUEST['action'] ) && ( 'save_image' === $_REQUEST['action'] ) ) ) : ?>
		<div class="wrap">
			<?php if ( ! isset( $_REQUEST['image_id'] ) ) : ?>
				<h2 id="addnew">Add New Image</h2>
			<?php else : ?>
				<h2>Edit Image Settings</h2>
			<?php endif; ?>
			
			<p>The uploaded image should be <?php echo "{$this->_options['width']}x{$this->_options['height']}"; ?> (<?php echo $this->_options['width']; ?> pixels wide by <?php echo $this->_options['height']; ?> pixels high).</p>
			<p>Images not matching the exact size will be resized and cropped to fit upon display.</p>
			
			<?php
				if ( isset( $this->_errors ) ) {
					$this->_options['attachment_id'] = $this->_attachment_id;
					$this->_options['url'] = $_POST[$this->_var . '-url'];
					$this->_options['order'] = $_POST[$this->_var . '-order'];
				}
				else if ( isset( $_REQUEST['image_id'] ) ) {
					$entry = $this->_options['image_ids'][$_REQUEST['image_id']];
					
					$this->_options['attachment_id'] = $entry['attachment_id'];
					$this->_options['url'] = $entry['url'];
					$this->_options['order'] = $entry['order'];
				}
				
				$image = '';
				if ( ! empty( $this->_options['attachment_id'] ) ) {
					require_once( TEMPLATEPATH . '/lib/file-utility/file-utility.php' );
					
					$image = iThemesFileUtility::resize_image( $this->_options['attachment_id'], $thumb_width, $thumb_height, true );
				}
				
				if ( isset( $this->_errors ) && is_array( $this->_errors ) ) {
					foreach ( (array) $this->_errors as $error )
						$this->_showErrorMessage( $error );
				}
			?>
			
			<form enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink; ?>">
				<?php wp_nonce_field( $this->_var . '-nonce' ); ?>
				<table class="form-table">
					<tr><th scope="row">Image</th>
						<td>
							<?php if ( ! empty( $image ) && ! is_wp_error( $image ) ) : ?>
								<img src="<?php echo $image['url']; ?>" /><br />
								
								<?php $this->_addHidden( 'attachment_id' ); ?>
								
								<p>Upload a new file to replace the current image.</p>
							<?php endif; ?>
							
							<?php $this->_addFileUpload( 'image_upload' ); ?>
						</td>
					</tr>
					<tr><th scope="row">Link URL</th>
						<td>
							<?php $this->_addTextBox( 'url', array( 'size' => '60' ) ); ?>
							<br />
							<i>Example: http://site.domain/</i>
						</td>
					</tr>
				</table>
				
				<p class="submit">
					<?php if ( ! isset( $_REQUEST['image_id'] ) ) : ?>
						<?php $this->_addSubmit( 'save_image', 'Add Image' ); ?>
					<?php else : ?>
						<?php $this->_addSubmit( 'save_image', 'Update Image Settings' ); ?>
						<?php $this->_addHiddenNoSave( 'image_id', $_REQUEST['image_id'] ); ?>
					<?php endif; ?>
				</p>
				
				<?php $this->_addHiddenNoSave( 'action', 'save_image' ); ?>
				
				<?php if ( ! empty( $this->_options['order'] ) ) : ?>
					<?php $this->_addHidden( 'order' ); ?>
				<?php endif; ?>
			</form>
		</div>
	<?php endif; ?>
	
	<?php if ( ! isset( $_REQUEST['image_id'] ) && ( ! isset( $_REQUEST['action'] ) || ( 'save_image' !== $_REQUEST['action'] ) ) ) : ?>
		<div class="wrap">
			<h2 id="featured-images-settings"><?php _e( 'Featured Images Settings', $this->_var ); ?></h2>
			
			<?php
				if ( isset( $this->_errors ) && is_array( $this->_errors ) ) {
					foreach ( (array) $this->_errors as $error )
						$this->showErrorMessage( $error );
				}
			?>
			
			<form enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink; ?>">
				<table class="form-table">
					<?php if ( true === $this->_defaults['variable_height'] ) : ?>
						<tr>
							<th scope="row">Featured&nbsp;Images&nbsp;Height</th>
							<td>
								<table>
									<tr>
										<td>Height in pixels:</td>
										<?php if ( ( ! empty( $_POST['save'] ) ) && ( intval( $_POST[$this->_var . '-height'] ) < 0 ) ) : ?>
											<td style="background-color:red;">
										<?php else: ?>
											<td>
										<?php endif; ?>
											<?php $this->_addTextBox( 'height', array( 'size' => '3', 'maxlength' => '5' ) ); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					<?php endif; ?>
					
					<tr>
						<th scope="row">Default&nbsp;URL</th>
						<td>
							<?php $this->_addTextBox( 'link', array( 'size' => '70' ) ); ?>
							<br />
							<i>This link will be used if an image doesn't have a URL.</i>
						</td>
					</tr>
					<tr>
						<th scope="row">Open&nbsp;URL&nbsp;in&nbsp;New&nbsp;Tab/Window</th>
						<td>
							<?php $this->_addCheckBox( 'open_new_window', '1' ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row">Fade Animation</th>
						<td>
							<div>The fade animation will show each of the images with a smooth fade transition between each image.</div>
							<div>If the animation is disabled, a single random image will be shown.</div>
							<br />
							
							<?php $this->_addCheckBox( 'enable_fade', '1' ); ?> Enable Fade
						</td>
					</tr>
					<tr id="fade-options">
						<th scope="row">Fade Options</th>
						<td>
							<div>The following options control the fade animation.</div>
							<div>If the animation is disabled, these options will not make any effect.</div>
							<br />
							
							<div>Choose an image sort order: <?php $this->_addDropDown( 'fade_sort', array( 'ordered' => 'As ordered (default)', 'alpha' => 'Alphabetical by file name', 'random' => 'Random' ) ); ?></div>
							<br />
							
							<table>
								<tr>
									<td>Length of time to display each image in seconds</td>
									<?php if ( ( ! empty( $_POST['save'] ) ) && ( floatval( $_POST[$this->_var . '-sleep'] ) <= 0 ) ) : ?>
										<td style="background-color:red;">
									<?php else: ?>
										<td>
									<?php endif; ?>
										<?php $this->_addTextBox( 'sleep', array( 'size' => '3', 'maxlength' => '5' ) ); ?>
									</td>
								</tr>
								<tr>
									<td>
										Length of time to fade each image in seconds
									</td>
									<?php if ( ( ! empty( $_POST['save'] ) ) && ( floatval( $_POST[$this->_var . '-fade'] ) <= 0 ) ) : ?>
										<td style="background-color:red;">
									<?php else: ?>
										<td>
									<?php endif; ?>
										<?php $this->_addTextBox( 'fade', array( 'size' => '3', 'maxlength' => '5' ) ); ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					<?php if( false === $this->_defaults['force_disable_overlay'] ) : ?>
						<tr id="text-overlay">
							<th scope="row">Text Overlay</th>
							<td>
							<div>Use this feature to overlay custom text on top of featured image(s).</div>
								<br />
								
								<div><?php $this->_addCheckBox( 'enable_overlay', '1' ); ?> Enable Text Overlay</div>
							</td>
						</tr>
					<?php endif; ?>
					<tr id="text-overlay-options">
						<th scope="row">Text Overlay Options</th>
						<td>
							<table>
								<tr><td>Text Horizontal Alignment:</td>
									<td><?php $this->_addDropDown( 'overlay_text_alignment', array( 'center' => 'Center (default)', 'left' => 'Left', 'right' => 'Right' ) ); ?></td>
								</tr>
								<tr><td>Text Vertical Position:</td>
									<td><?php $this->_addDropDown( 'overlay_text_vertical_position', array( 'bottom' => 'Bottom', 'middle' => 'Middle (default)', 'top' => 'Top' ) ); ?></td>
								</tr>
								<tr><td>Text Padding in Pixels:</td>
									<td><?php $this->_addTextBox( 'overlay_text_padding', array( 'size' => '4' ) ); ?></td>
								</tr>
							</table>
							
							<h3>Header Text</h3>
							<table>
								<tr><td>Text:</td>
									<td><?php $this->_addTextBox( 'overlay_header_text', array( 'size' => '40' ) ); ?></td>
								</tr>
								<tr><td>Size in pixels:</td>
									<td><?php $this->_addTextBox( 'overlay_header_size', array( 'size' => '4' ) ); ?></td>
								</tr>
								<tr><td>Color:</td>
									<td><?php $this->_addTextBox( 'overlay_header_color', array( 'size' => '7' ) ); ?>&nbsp;<?php $this->_addButton( 'show_overlay_header_color_picker', 'Show Picker' ); ?></td>
								</tr>
							</table>
							
							<h3>Subheader Text</h3>
							<table>
								<tr><td>Text:</td>
									<td><?php $this->_addTextBox( 'overlay_subheader_text', array( 'size' => '40' ) ); ?></td>
								</tr>
								<tr><td>Size in pixels:</td>
									<td><?php $this->_addTextBox( 'overlay_subheader_size', array( 'size' => '4' ) ); ?></td>
								</tr>
								<tr><td>Color:</td>
									<td><?php $this->_addTextBox( 'overlay_subheader_color', array( 'size' => '7' ) ); ?>&nbsp;<?php $this->_addButton( 'show_overlay_subheader_color_picker', 'Show Picker' ); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<br />
				
				<p class="submit"><?php $this->_addSubmit( 'save', 'Save' ); ?></p>
				<?php $this->_addHiddenNoSave( 'action', 'save' ); ?>
				<?php $this->_addUsedInputs(); ?>
				<?php wp_nonce_field( $this->_var . '-nonce' ); ?>
				
				<div id="overlay_header_color_ColorPickerWrapper" style="padding:10px; border:1px solid black; position:absolute; z-index:10; background-color:white; display:none;">
					<table><tr>
						<td style="vertical-align:top;"><div id="overlay_header_color_ColorMap"></div><br /><a href="javascript:void(0);" style="float:right;" id="overlay_header_color_hide_div">save selection</a></td>
						<td style="vertical-align:top;"><div id="overlay_header_color_ColorBar"></div></td>
						<td style="vertical-align:top;">
							<table>
								<tr><td colspan="3"><div id="overlay_header_color_Preview" style="background-color:#fff; width:95px; height:60px; padding:0; margin:0; border:solid 1px #000;"><br /></div></td></tr>
								<tr><td><input type="radio" id="overlay_header_color_HueRadio" name="overlay_header_color_Mode" value="0" /></td><td><label for="overlay_header_color_HueRadio">H:</label></td><td><input type="text" id="overlay_header_color_Hue" value="0" style="width: 40px;" /> &deg;</td></tr>
								<tr><td><input type="radio" id="overlay_header_color_SaturationRadio" name="overlay_header_color_Mode" value="1" /></td><td><label for="overlay_header_color_SaturationRadio">S:</label></td><td><input type="text" id="overlay_header_color_Saturation" value="100" style="width: 40px;" /> %</td></tr>
								<tr><td><input type="radio" id="overlay_header_color_BrightnessRadio" name="overlay_header_color_Mode" value="2" /></td><td><label for="overlay_header_color_BrightnessRadio">B:</label></td><td><input type="text" id="overlay_header_color_Brightness" value="100" style="width: 40px;" /> %</td></tr>
								<tr><td colspan="3" height="5"></td></tr>
								<tr><td><input type="radio" id="overlay_header_color_RedRadio" name="overlay_header_color_Mode" value="r" /></td><td><label for="overlay_header_color_RedRadio">R:</label></td><td><input type="text" id="overlay_header_color_Red" value="255" style="width: 40px;" /></td></tr>
								<tr><td><input type="radio" id="overlay_header_color_GreenRadio" name="overlay_header_color_Mode" value="g" /></td><td><label for="overlay_header_color_GreenRadio">G:</label></td><td><input type="text" id="overlay_header_color_Green" value="0" style="width: 40px;" /></td></tr>
								<tr><td><input type="radio" id="overlay_header_color_BlueRadio" name="overlay_header_color_Mode" value="b" /></td><td><label for="overlay_header_color_BlueRadio">B:</label></td><td><input type="text" id="overlay_header_color_Blue" value="0" style="width: 40px;" /></td></tr>
								<tr><td>#:</td><td colspan="2"><input type="text" id="overlay_header_color_Hex" value="FF0000" style="width: 60px;" /></td></tr>
							</table>
						</td>
					</tr></table>
				</div>
				
				<div id="overlay_subheader_color_ColorPickerWrapper" style="padding:10px; border:1px solid black; position:absolute; z-index:10; background-color:white; display:none;">
					<table><tr>
						<td style="vertical-align:top;"><div id="overlay_subheader_color_ColorMap"></div><br /><a href="javascript:void(0);" style="float:right;" id="overlay_subheader_color_hide_div">save selection</a></td>
						<td style="vertical-align:top;"><div id="overlay_subheader_color_ColorBar"></div></td>
						<td style="vertical-align:top;">
							<table>
								<tr><td colspan="3"><div id="overlay_subheader_color_Preview" style="background-color:#fff; width:95px; height:60px; padding:0; margin:0; border:solid 1px #000;"><br /></div></td></tr>
								<tr><td><input type="radio" id="overlay_subheader_color_HueRadio" name="overlay_subheader_color_Mode" value="0" /></td><td><label for="overlay_subheader_color_HueRadio">H:</label></td><td><input type="text" id="overlay_subheader_color_Hue" value="0" style="width: 40px;" /> &deg;</td></tr>
								<tr><td><input type="radio" id="overlay_subheader_color_SaturationRadio" name="overlay_subheader_color_Mode" value="1" /></td><td><label for="overlay_subheader_color_SaturationRadio">S:</label></td><td><input type="text" id="overlay_subheader_color_Saturation" value="100" style="width: 40px;" /> %</td></tr>
								<tr><td><input type="radio" id="overlay_subheader_color_BrightnessRadio" name="overlay_subheader_color_Mode" value="2" /></td><td><label for="overlay_subheader_color_BrightnessRadio">B:</label></td><td><input type="text" id="overlay_subheader_color_Brightness" value="100" style="width: 40px;" /> %</td></tr>
								<tr><td colspan="3" height="5"></td></tr>
								<tr><td><input type="radio" id="overlay_subheader_color_RedRadio" name="overlay_subheader_color_Mode" value="r" /></td><td><label for="overlay_subheader_color_RedRadio">R:</label></td><td><input type="text" id="overlay_subheader_color_Red" value="255" style="width: 40px;" /></td></tr>
								<tr><td><input type="radio" id="overlay_subheader_color_GreenRadio" name="overlay_subheader_color_Mode" value="g" /></td><td><label for="overlay_subheader_color_GreenRadio">G:</label></td><td><input type="text" id="overlay_subheader_color_Green" value="0" style="width: 40px;" /></td></tr>
								<tr><td><input type="radio" id="overlay_subheader_color_BlueRadio" name="overlay_subheader_color_Mode" value="b" /></td><td><label for="overlay_subheader_color_BlueRadio">B:</label></td><td><input type="text" id="overlay_subheader_color_Blue" value="0" style="width: 40px;" /></td></tr>
								<tr><td>#:</td><td colspan="2"><input type="text" id="overlay_subheader_color_Hex" value="FF0000" style="width: 60px;" /></td></tr>
							</table>
						</td>
					</tr></table>
				</div>
				
				<div style="display:none;">
					<?php
						$images = array( 'rangearrows.gif', 'mappoint.gif', 'bar-saturation.png', 'bar-brightness.png', 'bar-blue-tl.png', 'bar-blue-tr.png', 'bar-blue-bl.png', 'bar-blue-br.png', 'bar-red-tl.png',
							'bar-red-tr.png', 'bar-red-bl.png', 'bar-red-br.png', 'bar-green-tl.png', 'bar-green-tr.png', 'bar-green-bl.png', 'bar-green-br.png', 'map-red-max.png', 'map-red-min.png',
							'map-green-max.png', 'map-green-min.png', 'map-blue-max.png', 'map-blue-min.png', 'map-saturation.png', 'map-saturation-overlay.png', 'map-brightness.png', 'map-hue.png' );
						
						foreach( (array) $images as $image )
							echo '<img src="' . $this->_pluginURL . '/js/colorpicker/images/' . $image . "\" />\n";
					?>
					
				</div>
			</form>
		</div>
	<?php endif; ?>
<?php
		}
		
		
		// Form Functions ///////////////////////////
		
		function _newForm() {
			$this->_usedInputs = array();
		}
		
		function _addSubmit( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'submit';
			$options['name'] = $var;
			$options['class'] = ( empty( $options['class'] ) ) ? 'button-primary' : $options['class'];
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addButton( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'button';
			$options['name'] = $var;
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addTextBox( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'text';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addTextArea( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'textarea';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addFileUpload( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'file';
			$options['name'] = $var;
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addCheckBox( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'checkbox';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addMultiCheckBox( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'checkbox';
			$var = $var . '[]';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addRadio( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'radio';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addDropDown( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array();
			else if ( ! isset( $options['value'] ) || ! is_array( $options['value'] ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'dropdown';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addHidden( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'hidden';
			
			$this->_addSimpleInput( $var, $options, $override_value );
		}
		
		function _addHiddenNoSave( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['name'] = $var;
			
			$this->_addHidden( $var, $options, $override_value );
		}
		
		function _addDefaultHidden( $var ) {
			$options = array();
			$options['value'] = $this->defaults[$var];
			
			$var = "default_option_$var";
			
			$this->_addHiddenNoSave( $var, $options );
		}
		
		function _addUsedInputs() {
			$options['type'] = 'hidden';
			$options['value'] = implode( ',', $this->_usedInputs );
			$options['name'] = 'used-inputs';
			
			$this->_addSimpleInput( 'used-inputs', $options, true );
		}
		
		function _addSimpleInput( $var, $options = false, $override_value = false ) {
			if ( empty( $options['type'] ) ) {
				echo "<!-- _addSimpleInput called without a type option set. -->\n";
				return false;
			}
			
			
			$scrublist['textarea']['value'] = true;
			$scrublist['file']['value'] = true;
			$scrublist['dropdown']['value'] = true;
			
			$defaults = array();
			$defaults['name'] = $this->_var . '-' . $var;
			
			$var = str_replace( '[]', '', $var );
			
			if ( 'checkbox' === $options['type'] )
				$defaults['class'] = $var;
			else
				$defaults['id'] = $var;
			
			$options = $this->_merge_defaults( $options, $defaults );
			
			if ( ( false === $override_value ) && isset( $this->_options[$var] ) ) {
				if ( 'checkbox' === $options['type'] ) {
					if ( $this->_options[$var] == $options['value'] )
						$options['checked'] = 'checked';
				}
				elseif ( 'dropdown' !== $options['type'] )
					$options['value'] = $this->_options[$var];
			}
			
			if ( ( preg_match( '/^' . $this->_var . '/', $options['name'] ) ) && ( ! in_array( $options['name'], $this->_usedInputs ) ) )
				$this->_usedInputs[] = $options['name'];
			
			
			$attributes = '';
			
			if ( false !== $options )
				foreach ( (array) $options as $name => $val )
					if ( ! is_array( $val ) && ( ! isset( $scrublist[$options['type']][$name] ) || ( true !== $scrublist[$options['type']][$name] ) ) )
						if ( ( 'submit' === $options['type'] ) || ( 'button' === $options['type'] ) )
							$attributes .= "$name=\"$val\" ";
						else
							$attributes .= "$name=\"" . htmlspecialchars( $val ) . '" ';
			
			if ( 'textarea' === $options['type'] )
				echo '<textarea ' . $attributes . '>' . $options['value'] . '</textarea>';
			elseif ( 'dropdown' === $options['type'] ) {
				echo "<select $attributes>\n";
				
				foreach ( (array) $options['value'] as $val => $name ) {
					$selected = ( $this->_options[$var] == $val ) ? ' selected="selected"' : '';
					echo "<option value=\"$val\"$selected>$name</option>\n";
				}
				
				echo "</select>\n";
			}
			else
				echo '<input ' . $attributes . '/>';
		}
		
		
		// Plugin Functions ///////////////////////////
		
		function fadeImages() {
			require_once( TEMPLATEPATH . '/lib/file-utility/file-utility.php' );
			
			
			$this->_sortImages();
			
			$files = array();
			
			foreach ( (array) $this->_options['image_ids'] as $entry ) {
				$id = $entry['attachment_id'];
				
				if ( ! isset( $this->_options['link'] ) )
					$this->_options['link'] = '';
				
				$link = ( ! empty( $entry['url'] ) ) ? $entry['url'] : $this->_options['link'];
				
				if ( wp_attachment_is_image( $id ) ) {
					$file = get_attached_file( $id );
					$data = iThemesFileUtility::resize_image( $file, $this->_options['width'], $this->_options['height'], true );
					
					if ( ! is_array( $data ) && is_wp_error( $data ) )
						echo "<!-- Resize Error: " . $data->get_error_message() . " -->";
					else
						$files[] = array( 'image' => $data['url'], 'url' => $link );
				}
			}
			
			if ( 0 === count( $files ) )
				return;
			
			if ( isset( $this->_options['enable_fade'] ) && ( '1' == $this->_options['enable_fade'] ) && ( count( $files ) > 1 ) ) {
				$list = '';
				
				foreach ( (array) $files as $id => $file ) {
					if ( ! empty( $list ) )
						$list .= ",\n";
					
					if ( ! empty( $link ) )
						$list .= "{src: '{$file['image']}', href: '{$file['url']}'}";
					else
						$list .= "{src: '{$file['image']}'}";
				}
				
				
				if ( ! wp_script_is( 'jquery' ) )
					wp_print_scripts( 'jquery' );
				
				wp_enqueue_script( 'jquery-cross-slide', $this->_pluginURL . '/js/jquery.cross-slide.js' );
				wp_print_scripts( 'jquery-cross-slide' );
				
				$target = ( ! empty( $this->_options['open_new_window'] ) ) ? ', open_new_window: true' : '';
				
?>
	<script type='text/javascript'>
		/* <![CDATA[ */
			jQuery(document).ready(
				function() {
					jQuery('#featured-images-rotator').crossSlide(
						{sleep: <?php echo $this->_options['sleep']; ?>, fade: <?php echo $this->_options['fade']; ?><?php echo $target; ?>},
						[
							<?php echo "$list\n"; ?>
						]
					);
				}
			);
		/* ]]> */
	</script>
<?php
				
			}
			else
				shuffle( $files );
			
			
			if ( isset( $this->_options['overlay_text_vertical_position'] ) && ( 'bottom' === $this->_options['overlay_text_vertical_position'] ) )
				$title_overlay_vertical = "bottom: 0;\n";
			else
				$title_overlay_vertical = "top: 0;\n";
			
			if ( ! isset( $this->_options['overlay_header_text'] ) )
				$this->_options['overlay_header_text'] = '';
			if ( ! isset( $this->_options['overlay_subheader_text'] ) )
				$this->_options['overlay_subheader_text'] = '';
			
			
			$target = ( ! empty ( $this->_options['open_new_window'] ) ) ? ' target="_blank"' : '';
			
			$link_start = "\n";
			$link_end = "\n";
			
			if ( ! empty( $files[0]['url'] ) ) {
				$link_start = "					<a href=\"{$files[0]['url']}\" class=\"featured-images-link\"{$target}>\n";
				$link_end = "					</a>\n";
			}
			
			$overlay_text = "					<div class=\"featured-images-title-overlay-header\">\n$link_start";
			$overlay_text .= "						{$this->_options['overlay_header_text']}\n$link_end";
			$overlay_text .= "					</div>\n";
			
			if ( ! empty( $this->_options['overlay_subheader_text'] ) ) {
				$overlay_text .= "					<div class=\"featured-images-title-overlay-subheader\">\n$link_start";
				$overlay_text .= "						{$this->_options['overlay_subheader_text']}\n$link_end";
				$overlay_text .= "					</div>\n";
			}
			
			
?>
	<style type="text/css">
		#featured-images-rotator {
			background: url('<?php echo $files[0]['image']; ?>');
		}
		#featured-images-rotator,
		#featured-images-rotator-wrapper {
			width: <?php echo $this->_options['width']; ?>px;
			height: <?php echo $this->_options['height']; ?>px;
			text-align: left;
		}
		#featured-images-rotator-container .featured-images-link-overlay {
			height: <?php echo $this->_options['height']; ?>px;
			width: <?php echo $this->_options['width']; ?>px;
			position: absolute;
			top: 0;
			display: block;
		}
		#featured-images-rotator-container .featured-images-link {
			text-decoration: none;
		}
		#featured-images-rotator-container .featured-images-title-overlay {
			width: <?php echo ( $this->_options['width'] - ( $this->_options['overlay_text_padding'] * 2 ) ); ?>px;
			position: absolute;
			<?php echo $title_overlay_vertical; ?>
			text-align: <?php echo $this->_options['overlay_text_alignment']; ?>;
			padding: <?php echo $this->_options['overlay_text_padding']; ?>px;
			display: block;
		}
		#featured-images-rotator-container .featured-images-title-overlay-header {
			width: 100%;
			color: <?php echo $this->_options['overlay_header_color']; ?>;
			font-size: <?php echo $this->_options['overlay_header_size']; ?>px;
		}
		#featured-images-rotator-container .featured-images-title-overlay-subheader {
			width: 100%;
			color: <?php echo $this->_options['overlay_subheader_color']; ?>;
			font-size: <?php echo $this->_options['overlay_subheader_size']; ?>px;
		}
		#featured-images-rotator-container .featured-images-title-overlay-header {
			padding-bottom: <?php echo $this->_options['overlay_text_padding']; ?>px;
		}
		#featured-images-rotator-container .featured-images-title-overlay-header a {
			color: <?php echo $this->_options['overlay_header_color']; ?>;
			font-size: <?php echo $this->_options['overlay_header_size']; ?>px;
			line-height: 1;
		}
		#featured-images-rotator-container .featured-images-title-overlay-subheader a {
			color: <?php echo $this->_options['overlay_subheader_color']; ?>;
			font-size: <?php echo $this->_options['overlay_subheader_size']; ?>px;
			line-height: 1;
		}
	</style>
	
	<div id="featured-images-rotator-wrapper" style="position:relative;">
		<div id="featured-images-rotator-container" style="position:relative;">
			<div id="featured-images-rotator"><!-- placeholder --></div>
			
			<?php if ( ( false === $this->_defaults['force_disable_overlay'] ) && ! empty( $this->_options['enable_overlay'] ) ) : ?>
				
				<span class="featured-images-title-overlay">
					<?php if ( 'middle' === $this->_options['overlay_text_vertical_position'] ) : ?>
						
						<div style="display: table; height: <?php echo ( $this->_options['height'] - ( $this->_options['overlay_text_padding'] * 2 ) ); ?>px; width: <?php echo ( $this->_options['width'] - ( $this->_options['overlay_text_padding'] * 2 ) ); ?>px; #position: relative; overflow: hidden;">
							<div style="left: 0; #position: absolute; #top: 50%; display: table-cell; vertical-align: middle; width: <?php echo ( $this->_options['width'] - ( $this->_options['overlay_text_padding'] * 2 ) ); ?>px;">
								<div style="#position: relative; #top: -50%; width: <?php echo ( $this->_options['width'] - ( $this->_options['overlay_text_padding'] * 2 ) ); ?>px; display:block;">
									<?php echo "\n$overlay_text"; ?>
									
								</div>
							</div>
						</div>
					<?php else : ?>
						
						<?php echo "\n$overlay_text"; ?>
					<?php endif; ?>
					
				</span>
			<?php endif; ?>
			
			<?php if ( ! empty( $files[0]['url'] ) ) : ?>
				
				<?php $target = ( ! empty ( $this->_options['open_new_window'] ) ) ? ' target="_blank"' : ''; ?>
				
				<a href="<?php echo $files[0]['url']; ?>" class="featured-images-link featured-images-link-overlay"<?php echo $target; ?>>
					<!-- filler content -->
				</a>
			<?php endif; ?>
			
		</div>
	</div>
<?php
			
		}
		
		function _showStatusMessage( $message ) {
			
?>
	<div id="message" class="updated fade"><p><strong><?php echo $message; ?></strong></p></div>
<?php
			
		}
		
		function _showErrorMessage( $message ) {
			
?>
	<div id="message" class="error"><p><strong><?php echo $message; ?></strong></p></div>
<?php
			
		}
		
		function _merge_defaults( $values, $defaults, $force = false ) {
			if ( ! $this->_is_associative_array( $defaults ) ) {
				if ( ! isset( $values ) )
					return $defaults;
				
				if ( false === $force )
					return $values;
				
				if ( isset( $values ) || is_array( $values ) )
					return $values;
				return $defaults;
			}
			
			foreach ( (array) $defaults as $key => $val ) {
				if ( ! isset( $values[$key] ) )
					$values[$key] = null;
				
				$values[$key] = $this->_merge_defaults($values[$key], $val, $force );
			}
			
			return $values;
		}
		
		function _is_associative_array( &$array ) {
			if ( ! is_array( $array ) || empty( $array ) )
				return false;
			
			$next = 0;
			
			foreach ( $array as $k => $v )
				if ( $k !== $next++ )
					return true;
			
			return false;
		}
		
		
		// Utility Functions //////////////////////////
		
		function _sortImages() {
			if ( 'ordered' === $this->_options['fade_sort'] )
				uksort( $this->_options['image_ids'], array( &$this, '_orderedSort' ) );
			else if ( 'alpha' === $this->_options['fade_sort'] )
				uksort( $this->_options['image_ids'], array( &$this, '_alphaSort' ) );
			else
				uksort( $this->_options['image_ids'], array( &$this, '_randomSort' ) );
		}
		
		function _orderedSort( $a, $b ) {
			$a = $this->_options['image_ids'][$a];
			$b = $this->_options['image_ids'][$b];
			
			if ( $a['order'] < $b['order'] )
				return -1;
			
			return 1;
		}
		
		function _alphaSort( $a, $b ) {
			$a = basename( get_attached_file( $this->_options['image_ids'][$a]['attachment_id'] ) );
			$b = basename( get_attached_file( $this->_options['image_ids'][$b]['attachment_id'] ) );
			
			return strnatcasecmp( $a, $b );
		}
		
		function _randomSort( $a, $b ) {
			if ( mt_rand( 0, 1 ) === 1 )
				return -1;
			
			return 1;
		}
		
		function _initializeImages() {
			$dir_path = STYLESHEETPATH . '/images/random/';
			
			if ( false === ( $dir = @opendir( $dir_path ) ) ) {
				$dir_path = TEMPLATEPATH . '/images/random/';
				
				if ( false === ( $dir = @opendir( $dir_path ) ) )
					return;
			}
			
			require_once( TEMPLATEPATH . '/lib/file-utility/file-utility.php' );
			
			if ( ! ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) )
				return new WP_Error( 'upload_dir_failure', 'Unable to load images into the uploads directory: ' . $uploads['error'] );
			
			
			$this->_options['image_ids'] = array();
			
			$order = 1;
			
			while ( ( $file = readdir( $dir ) ) !== false ) {
				if ( is_file( $dir_path . $file ) && ( preg_match( '/gif$|jpg$|jpeg$|png$/i', $file ) ) ) {
					$filename = wp_unique_filename( $uploads['path'], basename( $file ) );
					
					// Move the file to the uploads dir
					$new_file = $uploads['path'] . "/$filename";
					if ( false === copy( $dir_path . $file, $new_file ) ) {
						closedir( $dir );
						return new WP_Error( 'copy_file_failure', 'The theme images were unable to be loaded into the uploads directory' );
					}
					
					// Set correct file permissions
					$stat = stat( dirname( $new_file ));
					$perms = $stat['mode'] & 0000666;
					@chmod( $new_file, $perms );
					
					// Compute the URL
					$url = $uploads['url'] . "/$filename";
					
					
					$wp_filetype = wp_check_filetype( $file );
					$type = $wp_filetype['type'];
					
					
					$file_obj['url'] = $url;
					$file_obj['type'] = $type;
					$file_obj['file'] = $new_file;
					
					
					$title = preg_replace( '/\.[^.]+$/', '', basename( $file ) );
					$content = '';
					
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					
					// use image exif/iptc data for title and caption defaults if possible
					if ( $image_meta = @wp_read_image_metadata( $new_file ) ) {
						if ( trim( $image_meta['title'] ) )
							$title = $image_meta['title'];
						if ( trim( $image_meta['caption'] ) )
							$content = $image_meta['caption'];
					}
					
					// Construct the attachment array
					$attachment = array(
						'post_mime_type' => $type,
						'guid' => $url,
						'post_title' => $title,
						'post_content' => $content
					);
					
					// Save the data
					$id = wp_insert_attachment( $attachment, $new_file );
					if ( ! is_wp_error( $id ) ) {
						wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $new_file ) );
					}
					
					
					$entry = array();
					$entry['attachment_id'] = $id;
					$entry['order'] = $order;
					$entry['url'] = '';
					
					$this->_options['image_ids'][] = $entry;
					
					
					$order++;
				}
			}
			
			closedir( $dir );
			
			
			$this->_save();
		}
	}
	
	new iThemesFeaturedImages2();
}

?>
