<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Http\Request;;
use App\Http\Resources\DocumentResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\UploadFileTrait;
use App\Jobs\ProcessDocument;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    use ApiResponseTrait , UploadFileTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Cache::remember('documents', 600, function () {
            return Document::all();
        });
        // $documents = Document::all();
        $data = DocumentResource::collection($documents);
        return $this->customeRespone($data, 'Done!', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        try {
            DB::beginTransaction();
            $filePath = null;
            if ($request->hasFile('file_path')) {
                $filePath = $this->uploadFile($request, 'documents', 'file_path');
                if (!$filePath) {
                return $this->customeRespone(null, 'Failed to upload file', 422);
            }
            }
            $document = Document::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'user_id' => $request->user_id,
            ]);
            $document->tags()->attach($request->tags);
            DB::commit();

            ProcessDocument::dispatch($document);

            Cache::forget('documents');
            Cache::put('documents', Document::all(), 600);

            $data = new DocumentResource($document);
            return $this->customeRespone($data, 'Document Created Successfully', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->customeRespone(null, 'Failed To Create', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $data = new DocumentResource($document);
        return $this->customeRespone($data, 'Done!', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        try {
            DB::beginTransaction();
            $filePath = null; //
            if ($request->hasFile('file_path')) {
                $filePath = $this->uploadFile($request, 'documents', 'file_path');
            }
            $document->update([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'user_id' => $request->user_id,
            ]);
            DB::commit();

            Cache::forget('documents');
            Cache::put('documents', Document::all(), 600);

            $data = new DocumentResource($document);
            return $this->customeRespone($data, 'Project Updated Successfully', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['message' => 'Something Error !'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();

        Cache::forget('documents');
        Cache::put('documents', Document::all(), 600);

        return $this->customeRespone(null, 'Document Deleted Successfully', 200);
    }
}
