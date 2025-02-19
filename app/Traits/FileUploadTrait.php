<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FileUploadTrait
{
    public function uploadImage(Request $request, string $inputName, $folder = '', ?string $oldPath = null, $path = '/uploads'): string
    {
        if ($request->hasFile($inputName)) {
            if ($oldPath) unlink(public_path($oldPath));
            $file = $request->{$inputName};
            $ext = $file->getClientOriginalExtension();
            $fileName = 'media_' . uniqid() . '.' . $ext;

            $file->move(public_path($path . '/' . $folder), $fileName);

            return $path . '/' . $folder . '/' . $fileName;
        }
        return '';
    }
}
