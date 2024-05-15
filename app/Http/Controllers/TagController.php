<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    use ApiResponseTrait, UploadFileTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        $data = TagResource::collection($tags);
        return $this->customeRespone($data, 'Done!', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        try {
            $tag = Tag::create([
                'name' => $request->name,

            ]);
            $data = new TagResource($tag);
            return $this->customeRespone($data, 'Tag Created Successfully', 201);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->customeRespone(null, 'Failed To Create', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $data = new TagResource($tag);
        return $this->customeRespone($data, 'Done!', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $tag->update([
                'name' => $request->name,
            ]);
            $data = new TagResource($tag);
            return $this->customeRespone($data, 'Tag Updated Successfully', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' => 'Something Error !'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return $this->customeRespone(null, 'Tag Deleted Successfully', 200);
    }
}
