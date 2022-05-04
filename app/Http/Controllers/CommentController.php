<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $comment = new Comment;
        $comment->text = $request->get('message');
        $comment->post_id = $request->get('post_id');
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return redirect()->back()->with('status', 'Your comment will be added soon');
    }
}
