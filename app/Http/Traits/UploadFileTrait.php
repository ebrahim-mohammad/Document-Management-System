<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Trait UploadFileTrait
 *
 * This trait provides a method to upload files to the server using the Laravel Storage facade.
 * It performs validation on the uploaded file and returns the public URL of the uploaded file if successful.
 */

trait UploadFileTrait
{
    /**
     * Uploads a file to the specified folder and returns the public URL of the uploaded file.
     *
     * @param Request $request
     * @param string $folderName
     * @param string $fileName
     * @return string|null
     */
    public function uploadFile(Request $request, $folderName, $fileName)
    {
        $validator = Validator::make($request->all(), [
            $fileName => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,csv|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('File upload validation failed: ' . $validator->errors());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file($fileName);
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $newFileName = time() . '.' . $extension;
        $filePath = $folderName . '/' . $newFileName;

        $file = $request->file($fileName);
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $newFileName = time() . '.' . $extension;
        $filePath = $folderName . '/' . $newFileName;

        try {
            if ($file->isValid()) {
                $uploaded = Storage::put($filePath, file_get_contents($file));

                if ($uploaded) {
                    return Storage::url($filePath);
                } else {
                    Log::error('Failed to upload file: ' . $filePath);
                    throw new \Exception('Failed to upload file');
                }
            } else {
                Log::error('Invalid file: ' . $file->getClientOriginalName());
                throw new \Exception('Invalid file');
            }
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return null;
        }
    }
}
