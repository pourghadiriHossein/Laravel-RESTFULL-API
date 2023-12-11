<?php

namespace App\Http\Controllers;

use App\Enum\Permissions;
use App\Enum\Roles;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'can:'.Permissions::VIEW_ANY_POST,
            'can:'.Permissions::CREATE_ANY_COMMENT,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postId = request()->input('post');
        $perPage = request()->input('perPage');
        if (Auth::guest()) {
            return $this->failResponse([], 403);
        }
        $query = Comment::query()
        ->select([
            'id',
            'user_id',
            'post_id',
            'parent_id',
            'child',
            'title',
            'text',
            ])
            ->where('post_id', $postId)
            ->where('parent_id', null)
            ->orderBy('id','desc');

        $comments = $query->paginate($perPage);
        return [
            'comments' => $comments,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, Post $post)
    {
        $data = $request->safe(['parent_id', 'title', 'text']);
        if (Auth::guest()) {
            return $this->failResponse([], 403);
        }
        $comment = new Comment([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $data['parent_id'],
            'title' => $data['title'],
            'text' => $data['text'],
        ]);
        if ($comment->save()){
            return $this->successResponse([
                'message' => 'Your create Accepted',
            ],200);
        }else {
            return $this->failResponse([
                'message' => 'Your Data have problem'
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return $this->successResponse([
            'comments' => $comment->children,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $data = $request->safe(['title', 'text']);
        if ($data['title'])
            $comment->title = $data['title'];
        if ($data['text'])
            $comment->text = $data['text'];
        if (Auth::user()->getRoleNames()[0] === Roles::ADMIN) {
            if ($comment->update()){
                return $this->successResponse([
                    'message' => 'Your Update Accepted',
                ],200);
            }else {
                return $this->failResponse([
                    'message' => 'Your Data have problem'
                ], 409);
            }
        }
        if (Auth::guest() || Auth::user()->id != $comment->user_id) {
            return $this->failResponse([], 403);
        }
        if ($comment->update()){
            return $this->successResponse([
                'message' => 'Your Update Accepted',
            ],200);
        }else {
            return $this->failResponse([
                'message' => 'Your Data have problem'
            ], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::user()->getRoleNames()[0] === Roles::ADMIN) {
            $comment->delete();
            return $this->successResponse([
                'message' => 'Comment Deleted',
            ],200);
        }
        if (Auth::guest() || Auth::user()->id != $comment->user_id) {
            return $this->failResponse([], 403);
        }
        $comment->delete();
        return $this->successResponse([
            'message' => 'Comment Deleted',
        ],200);
    }
}
