<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;;

use App\Http\Resources\CommentResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\UploadFileTrait;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    use ApiResponseTrait, UploadFileTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, $documentId)
    {
        $document = Document::findOrFail($documentId);
        $commentable = $document;

        try {
            DB::beginTransaction();
            $comment = $commentable->comments()->create([
                'user_id' => $request->user_id,
                'content' => $request->content,
                'commentable_id' => $request->commentable_id,
                'commentable_type' => $request->commentable_type,
            ]);
            DB::commit();
            $data = new CommentResource($comment);
            return $this->customeRespone($data, 'Comment Created Successfully', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->customeRespone(null, 'Failed To Create', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $data = new CommentResource($comment);
        return $this->customeRespone($data, 'Done!', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        try {
            DB::beginTransaction();
            $commentable = $comment->commentable;

            $comment->update([
                'user_id' => $request->user_id,
                'content' => $request->content,
                'commentable_id' => $request->commentable_id,
                'commentable_type' => $request->commentable_type,
            ]);

            DB::commit();

            $data = new CommentResource($comment);
            return $this->customeRespone($data, 'Comment Updated Successfully', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['message' => 'Something Error !'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->customeRespone(null, 'Comment Deleted Successfully', 200);
    }
}
