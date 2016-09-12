<?php
/**
 * Class Trimmer
 *
 * @package CNP
 */

namespace CNP;

/**
 * Class Trimmer
 *
 * @package ImageTrim
 */
class ImageTrim {
	/**
	 * Trims excess whitespace from images
	 *
	 * @param string $file URI of file.
	 * @param string $mime File mime-type.
	 * @param string $uri Destination URI.
	 * @param int    $id Attachment id.
	 *
	 * @return bool
	 */
	public static function trim( $file, $mime, $uri, $id ) {
		$img = null;

		switch ( $mime ) {
			case 'image/jpeg':
				$img = imagecreatefromjpeg( $file );
				break;
			case 'image/png':
				$img = imagecreatefrompng( $file );
				break;
			case 'image/gif':
				$img = imagecreatefromgif( $file );
				break;
			default:
				return false;
		}
		$borders = array(
			'top'    => 0,
			'bottom' => 0,
			'left'   => 0,
			'right'  => 0,
		);

		$y_limit = imagesy( $img );
		$x_limit = imagesx( $img );

		for ( $top = 0; $top < $y_limit; $top ++ ) {
			for ( $x = 0; $x < $x_limit; $x ++ ) {
				$borders['top'] = $top;

				$colors = imagecolorsforindex( $img, imagecolorat( $img, $x, $top ) );
				if ( 240 > $colors['red'] || 240 > $colors['green'] || 240 > $colors['blue'] ) {
					break 2;
				}
			}
		}

		for ( $bottom = 0; $bottom < $y_limit; $bottom ++ ) {
			for ( $x = 0; $x < $x_limit; ++ $x ) {
				$borders['bottom'] = $bottom;

				$colors = imagecolorsforindex( $img, imagecolorat( $img, $x, imagesy( $img ) - $bottom - 1 ) );
				if ( 240 > $colors['red'] || 240 > $colors['green'] || 240 > $colors['blue'] ) {
					break 2;
				}
			}
		}

		for ( $left = 0; $left < $x_limit; $left ++ ) {
			for ( $y = 0; $y < $y_limit; $y ++ ) {
				$borders['left'] = $left;

				$colors = imagecolorsforindex( $img, imagecolorat( $img, $left, $y ) );
				if ( 240 > $colors['red'] || 240 > $colors['green'] || 240 > $colors['blue'] ) {
					break 2;
				}
			}
		}

		for ( $right = 0; $right < $x_limit; $right ++ ) {
			for ( $y = 0; $y < $y_limit; $y ++ ) {
				$borders['right'] = $right;

				$colors = imagecolorsforindex( $img, imagecolorat( $img, $x_limit - $right - 1, $y ) );

				if ( 240 > $colors['red'] || 240 > $colors['green'] || 240 > $colors['blue'] ) {
					break 2;
				}
			}
		}

		$new_img = imagecreatetruecolor( imagesx( $img ) - ( $borders['left'] + $borders['right'] ), imagesy( $img ) - ( $borders['top'] + $borders['bottom'] ) );

		if ( ! $new_img ) {
			return false;
		}
		imagecopy( $new_img, $img, 0, 0, $borders['left'], $borders['top'], imagesx( $new_img ), imagesy( $new_img ) );

		switch ( $mime ) {
			case 'image/jpeg':
				imagejpeg( $new_img, $uri );
				break;
			case 'image/png':
				imagepng( $new_img, $uri );
				break;
			case 'image/gif':
				imagegif( $new_img, $uri );
				break;
			default:
				return false;
		}

		return true;
	}
}
