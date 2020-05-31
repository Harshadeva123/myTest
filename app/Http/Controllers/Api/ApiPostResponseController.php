<?php

namespace App\Http\Controllers\Api;

use App\Office;
use App\Post;
use App\PostResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiPostResponseController extends Controller
{
    public function viewComments(Request $request){
        $validationMessages = [
            'post_id.required' => 'Process invalid.Please refresh page and try again!',
            'post_id.numeric' => 'Process invalid.Please refresh page and try again!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_id' => 'required|numeric',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }
        $postId = $request['post_id'];
        $post = Post::find($postId);
        if($post != null){
            $comments = PostResponse::where('idPost',$postId)->where('idUser',Auth::user()->idUser)->where('status',1)->latest()->paginate(20);

            foreach ($comments as $comment) {

                if($comment->response_type == 1){
                    $comment['content'] = $comment['response'];
                }
                else{
                    $comment['content'] =  asset('').$comment->getPath();
                }
                $comment['response_type'] = $comment->response_type - 1;

                $comment->makeHidden('idUser')->toArray();
                $comment->makeHidden('idpost_response')->toArray();
                $comment->makeHidden('response')->toArray();
                $comment->makeHidden('attachment')->toArray();
                $comment->makeHidden('size')->toArray();
                $comment->makeHidden('categorized')->toArray();
                $comment->makeHidden('status')->toArray();
                $comment->makeHidden('full_path')->toArray();
                $comment->makeHidden('post')->toArray();
                $comment->makeHidden('idPost')->toArray();
                $comment->makeHidden('updated_at')->toArray();

            }
            return response()->json(['success' => $comments,'statusCode'=>0]);

        }
        else{
            return response()->json(['error' => 'Post invalid!','statusCode'=>-99]);

        }
    }

    public function store(Request $request){
        $validationMessages = [
            'post_id.required' => 'Process invalid.Please refresh page and try again!',
            'post_id.numeric' => 'Process invalid.Please refresh page and try again!',
            'comment.required' => 'Please write some text!',
            'comment.max' => 'Comment max characters size exeeded!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_id' => 'required|numeric',
            'comment' => 'required|max:10000',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }
        $user = Auth::user()->idUser;

        $post = Post::find(intval($request['post_id']));
        if($post == null){
            return response()->json(['error' =>'Process invalid.Please refresh page and try again!','statusCode'=>-99]);
        }

        //validation end

        $response = new PostResponse();
        $response->idPost = $post->idPost;
        $response->idUser = $user;
        $response->response = $request['comment'];
        $response->categorized = 0;// uncategorized when creating
        $response->is_admin = 0;// value for app users creating response
        $response->attachment = '';// no value for text
        $response->size = 0; // not value for text
        $response->response_type = 1;// text response
        $response->status = 1;
        $response->save();
        return response()->json(['success' =>'message sent','statusCode'=>0]);

    }


    public function storeAttachments(Request $request){
        $validationMessages = [
            'post_id.required' => 'Process invalid.Please refresh page and try again!',
            'post_id.numeric' => 'Process invalid.Please refresh page and try again!',
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
            'post_id' => 'required|numeric',
            'imageFiles.*' => 'nullable|file|image|mimes:jpeg,png,gif,webp|max:5048',
            'videoFiles.*' => 'nullable|mimes:mp4,mov,ogg,qt | max:20000',
            'audioFiles.*' => 'nullable|mimes:mpga,wav | max:10000',


        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }


        $post = Post::where('idPost',$request['post_id'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['error' => 'Process invalid.Please refresh page and try again!','statusCode'=>-99]);
        }

        $office = $post->user->idoffice;
        if($office == null){
            return response()->json(['error' =>'Process invalid.Please refresh page and try again!','statusCode'=>-99]);
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
                $response->idUser = Auth::user()->idUser;
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 0;// value for end user creating response
                $response->attachment = $imageName;
                $response->size = $image->getSize();
                $response->response_type = 2;// image
                $response->status = 1;
                $response->save();
            }
            return response()->json(['success' =>'image sent','statusCode'=>0]);

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
                $response->idUser = Auth::user()->idUser;
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 0;// value for end user creating response
                $response->attachment = $videoName;
                $response->size = $video->getSize();
                $response->response_type = 3;// video
                $response->status = 1;
                $response->save();
            }
            return response()->json(['success' =>'video sent','statusCode'=>0]);

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
                $response->idUser = Auth::user()->idUser;
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 0;// value for end user creating response
                $response->attachment = $audioName;
                $response->size = $audio->getSize();
                $response->response_type = 4;// audio
                $response->status = 1;
                $response->save();
            }
            return response()->json(['success' =>'audio sent','statusCode'=>0]);

        }
        //save audio end

        return response()->json(['success' =>'no file to send','statusCode'=>0]);
    }


}
