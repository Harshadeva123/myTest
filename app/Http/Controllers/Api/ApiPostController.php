<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\PostView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiPostController extends Controller
{
    public function getPosts(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'title_en';
        $fallBackText = 'text_en';
        if ($apiLang == 'si') {
            $lang = 'title_si';
            $langText = 'text_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'title_ta';
            $langText = 'text_ta';
        } else {
            $lang = 'title_en';
            $langText = 'text_en';
        }

        $user = Auth::user();
        $posts = Post::with(['apiAttachments'])->where(function ($q) use ($user) {
            $q->whereHas('postVillages', function ($q) use ($user) {
                $q->where('idvillage', $user->getType->idvillage);
            })->orWhereHas('postGramasewaDivision', function ($q) use ($user) {
                $q->where('idgramasewa_division', $user->getType->idgramasewa_division)->where('allChild', 1);
            })->orWhereHas('postPollingBooths', function ($q) use ($user) {
                $q->where('idpolling_booth', $user->getType->idpolling_booth)->where('allChild', 1);
            })->orWhereHas('postElectionDivisions', function ($q) use ($user) {
                $q->where('idelection_division', $user->getType->idelection_division)->where('allChild', 1);
            })->orWhereHas('postDistrict', function ($q) use ($user) {
                $q->where('iddistrict', $user->getType->iddistrict)->where('allChild', 1);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('ethnicities', 0)->orWhereHas('postEthnicities', function ($q) use ($user) {
                $q->where('idethnicity', $user->getType->idethnicity);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('religions', 0)->orWhereHas('postReligions', function ($q) use ($user) {
                $q->where('idreligion', $user->getType->idreligion);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('careers', 0)->orWhereHas('postCareers', function ($q) use ($user) {
                $q->where('idcareer', $user->getType->idcareer);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('educations', 0)->orWhereHas('postEducations', function ($q) use ($user) {
                $q->where('ideducational_qualification', $user->getType->idcareer);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('incomes', 0)->orWhereHas('postIncomes', function ($q) use ($user) {
                $q->where('idnature_of_income', $user->getType->idcareer);
            });
        })->where(function ($q) use ($user) {
            $q->where('job_sector', null)->orWhere('job_sector',$user->getType->is_government);
        })->where(function ($q) use ($user) {
            $q->where('preferred_gender', null)->orWhere('preferred_gender',$user->gender);
        })->where(function ($q) use ($user) {
            $q->where('minAge', 0)->orWhere('minAge','<=',$user->age);
        })->where(function ($q) use ($user) {
            $q->where('maxAge', 120)->orWhere('maxAge','>=',$user->age);
        })->where('expire_date','>',date('Y-m-d'))->select(['idPost','title_en','title_si','title_ta','text_en','text_si','text_ta'])->paginate(15);

        foreach ($posts as $post) {
            $post['title'] = $post[$lang] != null ? $post[$lang] : $post[$fallBack];
            $post['text'] = $post[$langText] != null ? $post[$langText] : $post[$fallBackText];
            unset($post->title_en);
            unset($post->title_si);
            unset($post->title_ta);
            unset($post->text_en);
            unset($post->text_si);
            unset($post->text_ta);
            unset($post->$lang);
        }
        return response()->json(['success' =>$posts,'statusCode'=>0]);

    }

    public function viewPost(Request $request){

        $postId  = $request['post_id'];
        $apiLang = $request['lang'];
        $fallBack = 'title_en';
        $fallBackText = 'text_en';
        if ($apiLang == 'si') {
            $lang = 'title_si';
            $langText = 'text_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'title_ta';
            $langText = 'text_ta';
        } else {
            $lang = 'title_en';
            $langText = 'text_en';
        }

        $isExist = PostView::where('idPost',$postId)->where('idUser',Auth::user()->idUser)->first();
        if($isExist == null){
            $postView = new PostView();
            $postView->idPost = $postId;
            $postView->idUser = Auth::user()->idUser;
            $postView->count = 1;
            $postView->save();
        }
        else{
            if(Post::find($postId)->isOnce == 1){
                return response()->json(['errors' => ['error' => 'Sorry! This post content can only view once!']]);
            }
            $isExist->count +=1;
            $isExist->save();
        }
        $post =   Post::with(['apiAttachments'])->select(['title_en','title_si','title_ta','text_en','text_si','text_ta','idPost'])->find(intval($postId));
        $post['title'] = $post[$lang] != null ? $post[$lang] : $post[$fallBack];
        $post['text'] = $post[$langText] != null ? $post[$langText] : $post[$fallBackText];
        unset($post->title_en);
        unset($post->title_si);
        unset($post->title_ta);
        unset($post->text_en);
        unset($post->text_si);
        unset($post->text_ta);
        unset($post->$lang);

        return response()->json(['success' =>$post,'statusCode'=>0]);


    }
}
