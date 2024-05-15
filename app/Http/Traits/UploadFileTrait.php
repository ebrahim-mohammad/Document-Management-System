<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
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
        // Validate the request input
        $validator = Validator::make($request->all(), [
            $fileName => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,csv|max:2048',
        ]);

        // If the validation fails, return a JSON response with the errors

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the uploaded file
        $file = $request->file($fileName);

        // Generate a new file name with the current timestamp

        $extension = $file->getClientOriginalExtension();
        $newFileName = uniqid() . '_' . $file->getClientOriginalName();
        $filePath = $folderName . '/' . $newFileName;

        // If the file is valid, store it using the Storage facade

        if ($file->isValid()) {
            $uploaded = Storage::put($filePath, file_get_contents($file));

        // If the file was successfully uploaded, return the public URL of the file

            if ($uploaded) {
                return Storage::url($filePath);
            }
        }

        // If the file could not be uploaded, return null
        return null;
    }
}
