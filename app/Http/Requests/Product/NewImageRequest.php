<?php

namespace App\Http\Requests\Product;

use App\Image;
use App\Product;
use App\Traits\Uuids;
use Illuminate\Foundation\Http\FormRequest;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class NewImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	
        return [ // image |  mimes:bmp,gif,jpeg,png,wbmp,webp
            'picture' => 'max:8',
            'picture.*' => 'required  | dimensions:min_width=480,min_height=480 | max:2600',
            'first'   => 'boolean|nullable',
        ];
        
    }
    
	
	public function persist(Product $product = null)
	{
		
		$isFirst = true;

		foreach ($this->picture as $img) {
		
			// upload image
			$uploadedImage = $img->store('products', 'public');
			$imgPath = \Storage::disk('public')->path($uploadedImage);
			
//			// Define upload path
//			$thumb = \Intervention\Image\Facades\Image::make( Storage::disk('public')->get($uploadedImage) );
//			$thumb->resize(480, 480)->stream(); //, function ($constraint) {$constraint->aspectRatio();}
//			Storage::disk('public')->put('/products/thumbnail/' . str_replace('products/' , '', $uploadedImage), $thumb);
			
			
			if ($this->stripExifData($imgPath)) {
				
				$images = session('product_images') ?? collect(); // return collection of images or empty collection
				
				$newimage = new Image;
				$newimage->id = Uuid::generate()->string;
				$newimage->image = $uploadedImage;
				$newimage->first = ($isFirst) ? 1 : 0;
				
				// adding images to old product
				if ($product && $product->exists) {
					
					// all existring images = not default
					if ($this->first && count($product->images()) > 0)
						$product->images()->update(['first' => 0]);
					
					$newimage->setProduct($product);
					$newimage->save();
					
				} else {
					
					// change all others to not be first
					if ($this->first) {
						$images->transform(function ($img) {
							$img->first = 0;
							
							return $img;
						});
					}
					
					try {
						$images->push($newimage); // put new offer
					} catch (\Exception $e) {
						session()->flash('errormessage', 'Error, saving image. ' . $e->getMessage());
					}
					
				
					session(['product_images' => $images]);
				}
				
			} else {
				session()->flash('errormessage', 'Please, remove EXIF data from the image.');
			}
		
			$isFirst = false;
		}
	
	}

    private function stripExifData($filename)
    {
        if (!file_exists($filename)) {
	        session() -> flash('errormessage', 'File "' . $filename . '" not found.');
	        return false;
//            throw new \Exception('File "' . $filename . '" not found.');
        }
        switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                try {
                    $img = imagecreatefromjpeg($filename);
                    imagejpeg($img, $filename, 100);
                } catch (\Exception $e) {
                    unlink($filename);
                }
                break;

            case 'png':
                try {
                    $img = imagecreatefrompng($filename);
                    imagepng($img, $filename, 9);
                } catch (\Exception $e) {
                    unlink($filename);
                }
                break;

           case 'gif':
                try {
                    $img = imagecreatefromgif($filename);
                    imagegif($img, $filename, 9);
                } catch (\Exception $e) {
                    unlink($filename);
                }

                break;

            default:
                unlink($filename);
	            session() -> flash('errormessage', 'File "' . $filename . '" is not valid jpg or png image. [127]');
	            return false;
//                throw new \Exception('File "' . $filename . '" is not valid jpg or png image.');
//                break;
        }

        return true;
    }
}
