<?php

/*
** Default max width and height
*/

define('MAX_WIDTH', 100);
define('MAX_HEIGHT', 100);

class ImageSuperimpose {
	private $image_1;
	private $image_2;

	public function set_images($image_1, $image_2)
	{
		if (isset($image_1) && isset($image_2)) {
			$this->image_1 = $image_1;
			$this->image_2 = $image_2;
		}
	}

	/*
	** Function to merge two images together
	**
	** Creates a new image of two images merged together
	*/

	public function merge($path, $raw = false)
	{

		/*
		** Get width, height and type of both images
		*/

		if (!$raw) {
			list($dst_img_width, $dst_img_height, $dst_img_type) = getimagesize($this->image_1);
		}
		else {
			list($dst_img_width, $dst_img_height, $dst_img_type) = getimagesizefromstring($this->image_1);
		}

		/*
		** Using GD functions create src image from extension type
		*/

		if (!$raw) {
			switch ($dst_img_type) {
				case IMAGETYPE_PNG:
					$dst = imagecreatefrompng($this->image_1);
					break;
				case IMAGETYPE_JPEG:
					$dst = imagecreatefromjpeg($this->image_1);
					break;
			}
		}
		else {
			$dst = imagecreatefromstring($this->image_1);
		}

		/*
		** Resize src image to same size as dest image to fit perfectly on dest
		** options passed to resize_img will return a gd image using dest height and width
		*/

		$src = $this->resize_img($this->image_2, [
			'return_gd' => true,
			'max_height' => $dst_img_height,
			'max_width' => $dst_img_width
		]);

		if (!is_array($src)) {

			if ($dst_img_type == IMAGETYPE_PNG) {
				imagealphablending($dst, false);
				imagesavealpha($dst, true);
			}

			/*
			** If imagecopy was successful then we save the image to the path
			*/

			if (imagecopy($dst, $src, 0, 0, 0, 0, $dst_img_width, $dst_img_height)) {
				$name = $path . time() . '.jpeg';
				imagejpeg($dst, $name);
				$result = $name;
			}
			else {
				$result = false;
			}

			/*
			** Free memory
			*/

			imagedestroy($dst);
			imagedestroy($src);

			/*
			** Return false or path to new file
			*/

			return $result;
		}
		else {
			//resize_img failed
			return false;
		}
	}

	public function resize_img($src_path, $opts = [])
	{

		/*
		** Full path to src must be set
		** Full path must be valid also
		*/

		if (isset($src_path)) {
			if (empty($src_path) || !file_exists($src_path)) {
				return [
					'success' => false,
					'message' => "src: {$src_path} is invalid (empty or file doesnt exist)."
				];
			}
		}

		if (!empty($opts)) {
			if (!isset($opts['return_gd'])) {
				if (!isset($opts['path'])) {
					return [
						'success' => false,
						'message' => 'return_gd not specified so path must be specified in opts arg'
					];
				}
				if (!file_exists($opts['path'])) {
					return [
						'success' => false,
						'message' => 'path specified does not exists'
					];
				}
			}
		}
		else {
			return [
				'success' => false,
				'message' => 'opts cannot be empty either return_gd, or path must be specified'
			];
		}

		if (isset($opts['max_height']) && isset($opts['max_width'])) {
			$max_width = $opts['max_width'];
			$max_height = $opts['max_height'];
		}
		else {
			$max_width = MAX_WIDTH;
			$max_height = MAX_HEIGHT;
		}

		/*
		** list function will assign variables from the result of getimagesize
		** the variables will be used to get the aspect ration of the src image
		*/

		list($src_width, $src_height, $src_type) = getimagesize($src_path);

		/*
		** Depending on the type of the src image we will create a GD image
		** to be used for create the new image
		*/

		switch ($src_type) {
			case IMAGETYPE_JPEG:
				$src_gd_image = imagecreatefromjpeg($src_path);
				break;
			case IMAGETYPE_PNG:
				$src_gd_image = imagecreatefrompng($src_path);
				break;
		}

		/*
		** The new image (resized image) can only be created if 
		** the switch statement above creates a image based on the
		** src_type
		*/

		if (!$src_gd_image) {
			return false;
		}

		/*
		** The new image maintains the same aspect ratio as the src image
		**
		** To calculate the dimensions of the new image, the aspect ratios of the src image
		** and the ideal (new) image are calculated
		**
		** The aspect ratio of an image is its width divided by its height
		*/

		$src_aspect_ratio = $src_width / $src_height;
		$new_aspect_ratio = $max_width / $max_height;

		/*
		** Using the aspect ratio we calculate the ideal width and height of the new image
		*/

		if ($src_width <= $max_width && $src_height <= $max_height) {
			$new_image_width = $max_width;
			$new_image_height = $max_height;
		}
		else if ($new_aspect_ratio > $src_aspect_ratio) {
			$new_image_width = (int) ($max_height) * $src_aspect_ratio;
			$new_image_height = $max_height;
		}
		else {
			$new_image_width = $max_width;
			$new_image_height = (int) ($max_height / $src_aspect_ratio);
		}

		/*
		** Using the ideal width and height of the new image
		** Use the GD(graphics drawing) lib function imagecreatetruecolor to create a new image
		**
		*/

		$new_gd_image = imagecreatetruecolor($new_image_width, $new_image_height);

		if ($src_type == IMAGETYPE_PNG) {
			imagealphablending($new_gd_image, false);
			imagesavealpha($new_gd_image, true);
		}

		/*
		** Next, imagecopyresampled is used to copy a rectangular area from the src image (width, height)
		** and place it it in a rectangular area of the new image of width and height
		*/

		imagecopyresampled($new_gd_image, 
			$src_gd_image, 
			0, 0, 0, 0, 
			$new_image_width, 
			$new_image_height, 
			$src_width, 
			$src_height
		);

		/*
		** By default the function saves the image created
		** to the specified path in the array opts argument
		*/

		if (isset($opts['return_gd'])) {
			if ($opts['return_gd']) {
				return $new_gd_image;
			}
		}

		/*
		** Depneding on the src type we will save the image in the media folder
		** with filename prefixed by the current time (php time function) and
		** postfixed by the extension of the src image
		*/

		switch ($src_type) {
			case IMAGETYPE_PNG:
				imagepng($new_gd_image, $opts['path'] . time() . '.png', 9);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($new_gd_image, $opts['path'] . time() . '.jpeg', 90);
				break;
			
		}

		/*
		** Check if images were destroyed successfully
		*/

		if (imagedestroy($src_gd_image) && imagedestroy($new_gd_image)) {
			return [
				'success' => true,
				'message' => 'Images destroyed, memory freed'
			];
		}
		else {
			return [
				'success' => false,
				'message' => 'Failed to destroy images'
			];
		}
	}

}