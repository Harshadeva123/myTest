<?php

namespace App\Http\Controllers;

use App\Office;
use App\Post;
use App\PostResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $validationMessages = [
            'post_no.required' => 'Process invalid.Please refresh page and try again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and try again!',
            'user_id.required' => 'Process invalid.Please refresh page and try again!',
            'user_id.numeric' => 'Process invalid.Please refresh page and try again!',
            'comment.required' => 'Please write some text!',
            'comment.max' => 'Comment max characters size exeeded!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_no' => 'required|numeric',
            'user_id' => 'required|numeric',
            'comment' => 'required|max:10000',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $post = Post::where('post_no',$request['post_no'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

        //validation end

        $response = new PostResponse();
        $response->idPost = $post->idPost;
        $response->idUser = $request['user'];
        $response->idUser = $request['user_id'];
        $response->response = $request['comment'];
        $response->categorized = 0;// uncategorized when creating
        $response->is_admin = 1;// value for admin creating response
        $response->attachment = '';// no value for text
        $response->size = 0; // not value for text
        $response->response_type = 1;// text response
        $response->status = 1;
        $response->save();
        return response()->json(['success' =>'message sent']);

    }

    public function storeAttachments(Request $request){
        $validationMessages = [
            'post_no.required' => 'Process invalid.Please refresh page and try again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and try again!',
            'user_id.required' => 'Process invalid.Please refresh page and try again!',
            'user_id.numeric' => 'Process invalid.Please refresh page and try again!',
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $post = Post::where('post_no',$request['post_no'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

        $office = $post->user->idoffice;
        if($office == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

//save images
        $images = $request->file('imageFiles');
        if (!empty($images)) {
            foreach ($images as $image) {
                $imageName = 'image' . uniqid() . rand(10, 100) . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/comments/images', $image, $imageName);

                $response = new PostResponse();
                $response->idPost = $post->idPost;
                $response->idUser = $request['user'];
                $response->idUser = $request['user_id'];
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 1;// value for admin creating response
                $response->attachment = $imageName;
                $response->size = $image->getSize();
                $response->response_type = 2;// image
                $response->status = 1;
                $response->save();
            }
        }
        //save images end

        //save video
        $videos = $request->file('videoFiles');
        if (!empty($videos)) {
            foreach ($videos as $video) {
                $videoName = 'video' . uniqid() . rand(10, 100) . '.' . $video->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/comments/videos', $video, $videoName);

                $response = new PostResponse();
                $response->idPost = $post->idPost;
                $response->idUser = $request['user'];
                $response->idUser = $request['user_id'];
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 1;// value for admin creating response
                $response->attachment = $videoName;
                $response->size = $video->getSize();
                $response->response_type = 3;// video
                $response->status = 1;
                $response->save();
            }
        }
        //save video end

        //save audio
        $audios = $request->file('audioFiles');
        if (!empty($audios)) {
            foreach ($audios as $audio) {
                $audioName = 'audio' . uniqid() . rand(10, 100) . '.' . $audio->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/comments/audios', $audio, $audioName);

                $response = new PostResponse();
                $response->idPost = $post->idPost;
                $response->idUser = $request['user'];
                $response->idUser = $request['user_id'];
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 1;// value for admin creating response
                $response->attachment = $audioName;
                $response->size = $audio->getSize();
                $response->response_type = 4;// audio
                $response->status = 1;
                $response->save();
            }
        }
        //save audio end

        return response()->json(['success' =>'file sent']);
    }

    public function getCommentByUserAndPost(Request $request){
        $validator = \Validator::make($request->all(), [
            'post_no' => 'required|numeric',
            'user_id' => 'required|numeric',
        ],
        [
            'post_no.required' => 'Process invalid.Please refresh page and try again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and try again!']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $post = Post::where('post_no',$request['post_no'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

        //validation end

       $responses =  $post->responses()->with('post')->where('idPost',1)->where('idUser',$request['user_id'])->where('status',1)->orderBy('created_at')->get();
       return response()->json(['success' =>$responses]);
    }
}
