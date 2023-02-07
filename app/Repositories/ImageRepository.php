<?php

namespace App\Repositories;

use Intervention\Image\Facades\Image as InterImage;

class ImageRepository
{
    public function uploadImage($path, $file)
    {
        dd($path);
        if (!is_dir($path)) {
            mkdir($path);
        }

        $url = $file->getClientOriginalName();
        $filename = pathinfo($url, PATHINFO_FILENAME);
        $urlExtension = $file->getClientOriginalExtension();

        $i = 0;
        $fullpathfile = $path . '/' . $url;
        while (file_exists($fullpathfile)) {
            $i++;
            $url = $filename . '-' . (string)$i . '.' . $urlExtension;
            $fullpathfile = $path . '/' . $url;
        }
        $img = InterImage::make($file->path());
        $img->resize(1000, 1000, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '/' . $url);

        return $url;
    }

    public function destroy($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    filetype($dir . "/" . $object) == "dir" ?
                        $this->destroy($dir . "/" . $object)
                        :
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
