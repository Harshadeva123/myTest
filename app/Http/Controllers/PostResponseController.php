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

    public function viewComment(Request $request){
        $user = $request['user'];
        $postNo = $request['post_no'];
        $post = Post::where('idoffice',Auth::user()->idoffice)->where('post_no',$postNo)->first();
        if($post == null || $user == null){
            return redirect()->back()->with(['error'=>'Invalid post or user']);
        }
        else{
            $commenters = $post->responses()->where('idUser',$user)->where('status',1)->get();
            return view('post.comment')->with(['title'=>'View Comment','commenters'=>$commenters]);
        }
    }

    public function store(Request $request){

    }

    public function storeAttachments(Request $request){
        $validationMessages = [
            'post_no.required' => 'Process invalid.Please refresh page and tty again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and tty again!',
            'user_id.required' => 'Process invalid!',
            'user_id.numeric' => 'Process invalid!',
            'imageFiles.*.file' => 'Image file invalid!',
            'imageFiles.*.image' => 'Image file invalid!',
            'imageFiles.*.mimes' => 'Image file format invalid!',
            'imageFiles.*.max' => 'Image file should less than 5MB!',
            'videoFiles.*.file' => 'Video file invalid!',
            'videoFiles.*.image' => 'Video file invalid!',
            'videoFiles.*.mimes' => 'Video file format invalid!',
            'videoFiles.*.max' => 'Video file should less than 20MB!',
            'audioFiles.*.file' => 'Audio file invalid!',
            'audioFiles.*.image' => 'Audio file invalid!',
            'audioFiles.*.mimes' => 'Audio file format invalid!',
            'audioFiles.*.max' => 'Audio file should less than 20MB!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_no' => 'required|numeric',
            'user_id' => 'required|numeric',
            'imageFiles.*' => 'nullable|file|image|mimes:jpeg,png,gif,webp|max:5048',
            'videoFiles.*' => 'nullable|mimes:mp4,mov,ogg,qt | max:20000',
            'audioFiles.*' => 'nullable|mimes:mpga,wav | max:10000',


        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }
}
