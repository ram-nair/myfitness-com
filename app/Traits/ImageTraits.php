<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Storage;
use File;

/**
 * This is a trait use full to store image either in s3 or in local storage
 *
 * @author Shainu
 * Usage : $resizedImage = $this->multiImage($request->file('avatar'), 'avatar', 'multi_');
 */
trait ImageTraits {

    private function singleImage($image, $path, $fileName) {
        $imageName = $fileName . rand(1, time()) . '.' . $image->getClientOriginalExtension();

        try {
            if (!env('CDN_ENABLED', false)) {
                $image = $image->getRealPath();
                $path = $path . '/';
                $disk = 'public';
            } else {
                $path = env('CDN_FILE_DIR', 'dev/test/') . $path . '/';
                $disk = 's3';
            }
            $img = Image::make($image);
            Storage::disk('public_uploads')->put($path . $imageName, $img->stream()->detach());
        
            
            //Storage::disk($disk)->put($path . $imageName, $img->stream()->detach(), 'public');
            return $imageName;
        } catch (Exception $e) {
            return false;
        }
    }

}
