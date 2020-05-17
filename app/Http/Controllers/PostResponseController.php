<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostResponseController extends Controller
{
    public function viewComments(Request $request){
        $postNo = $request['post_no'];
        $post = Post::where('post_no',$postNo)->where('idoffice',Auth::user()->idoffice)->first();
        if($post != null){
            $commenters = $post->responses()->where('is_admin',0)->get()->groupBy('idUser');
            return view('post.comments')->with(['title'=>'View Comments','commenters'=>$commenters]);
        }
        else{
            return redirect()->back()->with(['error'=>'Invalid post']);
        }
    }
}
