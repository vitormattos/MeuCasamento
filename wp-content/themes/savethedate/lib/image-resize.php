<?php

/*
Plugin Name: iThemes' Theme Image Resizer
Plugin URI: 
Description: Resizes images for easy integration of sized images into the theme.
Version: 1.0.2
Author: Chris Jean
Author URI: http://ithemes.com/

1.0.1 - 8-01-08
1.0.2 - 10-13-08
	Modified the get_children call to adapt for the code switch from 2.5.1 to 2.6
*/


if ( ! class_exists( 'PortfolioThemeImageResizer' ) ) {
	class PortfolioThemeImageResizer {
		var $var = "portfolio_theme_image_resizer";
		var $name = "Portfolio Theme Image Resizer";
		
		
		function PortfolioThemeImageResizer() {
			
		}
		
		
		function resizeImageCrop( $source, $width = false, $height = false, $destination = false ) {
			$source = $this->getAbsolutePath( $source );
			
			if ( is_wp_error( $source ) )
				return $source;
			
			
			if ( false === $destination )
				$destination = $this->getDestinationName( $source, 'resizecrop', $width, $height );
			
			if ( file_exists( $destination ) ) {
				if ( filemtime( $source ) > filemtime( $destination ) )
					unlink( $destination );
				else
					return $this->getImageURL( $destination );
			}
			
			
			$imageData = $this->createImageFromFile( $source );
			
			if ( is_wp_error( $imageData ) )
				return $imageData;
			
			
			$image = $imageData['data'];
			$imageType = $imageData['type'];
			
			
			if ( ! is_numeric( $width ) && ! is_numeric( $height ) ) {
				return $this->outputImage( $image, $imageType, $source, $destination );
			}
			
			
			$curwidth = imagesx( $image );
			$curheight = imagesy( $image );
			
			
			if ( false === $width ) {
				$newheight = $height;
				$newwidth = (int) ( ( $height / $curheight ) * $curwidth );
			}
			else if ( false === $height ) {
				$newwidth = $width;
				$newheight = (int) ( ( $width / $curwidth ) * $curheight );
			}
			else {
				if ( ( $curwidth == $width ) && ( $curheight == $height ) )
					return $this->outputImage( $image, $imageType, $source, $destination );
				
				$curRatio = $curwidth / $curheight;
				$newRatio = $width / $height;
				
				if ( $curRatio > $newRatio ) {
					$newheight = $height;
					$newwidth = (int) ( ( $height / $curheight ) * $curwidth );
				}
				else {
					$newwidth = $width;
					$newheight = (int) ( ( $width / $curwidth ) * $curheight );
				}
			}
			
			$thumb = imagecreatetruecolor( $width, $height );
			imagecopyresampled( $thumb, $image, (int) ( ( $width - $newwidth ) / 2 ), (int) ( ( $height - $newheight ) / 2 ), 0, 0, $newwidth, $newheight, $curwidth, $curheight );
			
			return $this->outputImage( $thumb, $imageType, $source, $destination );
		}
		
		function resizeImageNoCrop( $source, $width = false, $height = false, $destination = false ) {
			$source = $this->getAbsolutePath( $source );
			
			if ( is_wp_error( $source ) )
				return $source;
			
			
			if ( ! $destination )
				$destination = $this->getDestinationName( $source, 'resize', $width, $height );
			
			if ( file_exists( $destination ) ) {
				if ( filemtime( $source ) > filemtime( $destination ) )
					unlink( $destination );
				else
					return $this->getImageURL( $destination );
			}
			
			
			$imageData = $this->createImageFromFile( $source );
			
			if ( is_wp_error( $imageData ) )
				return $imageData;
			
			
			$image = $imageData['data'];
			$imageType = $imageData['type'];
			
			
			$curwidth = imagesx( $image );
			$curheight = imagesy( $image );
			
			if ( is_numeric( $width ) ) {
				if ( is_numeric( $height ) ) {
					$newheight = (int) ( ( $width / $curwidth ) * $curheight );
					$newwidth = (int) ( ( $height / $curheight ) * $curwidth );
					
					if ( $newheight > $height )
						$width = $newwidth;
					else
						$height = $newheight;
				}
				else
					$height = (int) ( ( $width / $curwidth ) * $curheight );
			}
			elseif ( is_numeric( $height ) )
				$width = (int) ( ( $height / $curheight ) * $curwidth );
			else {
				return $this->outputImage( $image, $imageType, $source, $destination );
			}
			
			$thumb = imagecreatetruecolor( $width, $height );
			imagecopyresampled( $thumb, $image, 0, 0, 0, 0, $width, $height, $curwidth, $curheight );
			
			return $this->outputImage( $thumb, $imageType, $source, $destination );
		}
		
		function outputImage( $image, $imageType, $source, $destination ) {
			$success = false;
			
			if ( IMAGETYPE_JPEG === $imageType )
				$success = imagejpeg( $image, $destination, 100 );
			else if ( IMAGETYPE_PNG === $imageType )
				$success = imagepng( $image, $destination, 0 );
			else if ( IMAGETYPE_GIF === $imageType )
				$success = imagegif( $image, $destination );
			else {
				imagedestroy( $image );
				return new WP_Error( 'output_image_fail', 'Unrecognized image type; unable to output image data' );
			}
			
			imagedestroy( $image );
			
			
			if ( $success )
				return $this->getImageURL( $destination );
			
			return new WP_Error( 'cannot_write_file', "Unable to write to file: $destination" );
		}
		
		function createImageFromFile( $path ) {
			$info = @getimagesize( $path );
			
			if ( false === $info )
				return new WP_Error( 'file_open_failed', 'Unable to open the image file' );
			
			$functions = array(
					IMAGETYPE_JPEG => 'imagecreatefromjpeg',
					IMAGETYPE_PNG => 'imagecreatefrompng',
					IMAGETYPE_GIF => 'imagecreatefromgif'
				);
			
			if ( ! $functions[$info[2]] )
				return new WP_Error( 'file_read_failed', 'Unable to read image data from file' );
			
			if ( ! function_exists( $functions[$info[2]] ) )
				return new WP_Error( 'unknown_image_type', 'An unknown image type was found. Only gif, jpg, and png formats are supported.' );
			
			return array( 'data' => $functions[$info[2]]( $path ), 'type' => $info[2] );
		}
		
		function getDestinationName( $source, $type, $width, $height ) {
			if ( preg_match( '/(.+)\.(\w+)$/', $source, $matches ) )
				return $matches[1] . "-$type-$width-$height." . $matches[2];
			
			return $source;
		}
		
		function getImageURL( $path ) {
			$absPath = preg_replace( '/\\//', '\\/', ABSPATH );
			
			if ( preg_match( "/$absPath(.+)/", $path, $matches ) )
				return get_option('siteurl') . '/' . $matches[1];
			
			return new WP_Error( 'cannot_make_image_url', 'Unable to change path ' . $path . ' to a URL' );
		}
		
		function getAbsolutePath( $source ) {
			if ( file_exists( $source ) )
				return $source;
			
			$source = preg_replace( '/https?:\\/\\/[^\\/]+(.+)/', '$1', $source );
			
			if ( preg_match( '/https?:\\/\\/[^\\/]+(.+)/', WP_CONTENT_URL, $matches ) ) {
				$folder = $matches[1];
				$folder = preg_replace( '/\\//', '\\/', $folder );
				
				if ( preg_match( '/(.+)' . $folder . '$/', WP_CONTENT_DIR, $matches ) )
					if ( file_exists( $matches[1] . $source ) )
						return $matches[1] . $source;
			}
			
			return new WP_Error( 'cannot_find_image_path', 'Unable to find absolute image path from given source path: ' . $source );
		}
	}
}


if ( class_exists( 'PortfolioThemeImageResizer' ) ) {
	$portfolioThemeImageResizer = new PortfolioThemeImageResizer();
}


if ( ! function_exists( 'portfolio_image_resize' ) ) {
	function portfolio_image_resize( $source, $width = 100, $height = 100 ) {
		global $portfolioThemeImageResizer;
		
		return $portfolioThemeImageResizer->resizeImageCrop( $source, $width, $height );
	}
}

if ( ! function_exists( 'portfolio_image_resize_no_crop' ) ) {
	function portfolio_image_resize_no_crop( $source, $width = 100, $height = 100 ) {
		global $portfolioThemeImageResizer;
		
		return $portfolioThemeImageResizer->resizeImageNoCrop( $source, $width, $height );
	}
}

if ( ! function_exists( 'portfolio_get_post_image' ) ) {
	function portfolio_get_post_image( $id, $customField = false, $width = 100, $height = 100, $crop = true ) {
		global $portfolioThemeImageResizer, $wp_version;
		
		
		$image = false;
		
		if ( false !== $customField )
			$image = get_post_meta( $id, $customField, true);
		
		if ( empty( $image ) ) {
			if ( version_compare ( $wp_version, '2.6', '>=' ) )
				$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) );
			else
				$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order, ID' ) );
			
			if ( ! empty( $attachments ) )
				$image = $attachments[ key( $attachments ) ]->guid;
		}
		
		
		if ( ! empty( $image ) ) {
			if ( $crop )
				$resize = portfolio_image_resize( $image, $width, $height );
			else
				$resize = portfolio_image_resize_no_crop( $image, $width, $height );
			
			if ( is_wp_error( $resize ) ) {
				echo '<!-- Portfolio Theme Error: ' . $resize->get_error_message() . " -->\n\n";
				
				return $image;
			}
		}
		
		
		if ( false === $image ) {
			echo "<!-- Portfolio Theme Error: An unknown error happened while trying to resize the image for post id '$id' custom field '$customField' -->\n\n";
			
			return '';
		}
		
		
		return $resize;
	}
}

?>