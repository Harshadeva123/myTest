<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\Category;
use App\ElectionDivision;
use App\GramasewaDivision;
use App\PollingBooth;
use App\Post;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function categoryWise(Request $request)
    {
        $electionDivisions = ElectionDivision::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();

        return view('report.category_wise')->with(['categories' => $categories, 'title' => 'Reports : Category Wise', 'electionDivisions' => $electionDivisions]);

    }

    public function locationWise(Request $request)
    {
        $electionDivisions = ElectionDivision::where('status', 1)->get();
        return view('report.location_wise')->with(['title' => 'Reports : Location Wise', 'electionDivisions' => $electionDivisions]);

    }

    public function locationWiseChart(Request $request)
    {
        $village = $request['village'];
        $gramasewaDivision = $request['gramasewaDivision'];
        $pollingBooth = $request['pollingBooth'];
        $electionDivision = $request['electionDivision'];
        $district = Auth::user()->office->iddistrict;

        if ($village != null) {
            $questions = Analysis::with(['category'])->where('idoffice', Auth::user()->idoffice)->where('iddistrict', Auth::user()->office->iddistrict)->whereIn('idsub_category', [1, 2, 3])->where('idvillage', $village)->where('idgramasewa_division', $gramasewaDivision)->get()->groupBy('idcategory');

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->
            whereHas('beneficialVillage', function ($q) use ($village) {
                $q->where('idvillage', $village);
            })->orWhereHas('beneficialGramasewaDivision', function ($q) use ($village) {
                $q->where('idgramasewa_division', Village::find($village)->idgramasewa_division)->where('allChild', 1);
            })->orWhereHas('beneficialPollingBooth', function ($q) use ($village) {
                $q->where('idpolling_booth', GramasewaDivision::find(Village::find($village)->idgramasewa_division)->idpolling_booth)->where('allChild', 1);
            })->orWhereHas('beneficialDistrict', function ($q) {
                $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($gramasewaDivision != null) {

            $questions = Analysis::with(['category'])->where('iddistrict', Auth::user()->office->iddistrict)->where('idoffice', Auth::user()->idoffice)->whereIn('idsub_category', [1, 2, 3])->where('idgramasewa_division', $gramasewaDivision)->get()->groupBy('idcategory');

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->
            whereHas('beneficialGramasewaDivision', function ($q) use ($gramasewaDivision) {
                $q->where('idgramasewa_division', $gramasewaDivision);
            })->orWhereHas('beneficialPollingBooth', function ($q) use ($gramasewaDivision) {
                $q->where('idpolling_booth', GramasewaDivision::find($gramasewaDivision)->idpolling_booth)->where('allChild', 1);
            })->orWhereHas('beneficialElectionDivision', function ($q) use ($gramasewaDivision) {
                $q->where('idelection_division', ElectionDivision::find(GramasewaDivision::find($gramasewaDivision)->idpolling_booth)->idelection_division)->where('allChild', 1);
            })->orWhereHas('beneficialDistrict', function ($q) {
                $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($pollingBooth != null) {

            $questions = Analysis::with(['category'])->where('iddistrict', Auth::user()->office->iddistrict)->where('idoffice', Auth::user()->idoffice)->whereIn('idsub_category', [1, 2, 3])->where('idpolling_booth', $pollingBooth)->get()->groupBy('idcategory');

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->
            whereHas('beneficialPollingBooth', function ($q) use ($pollingBooth) {
                $q->where('idpolling_booth', $pollingBooth);
            })->orWhereHas('beneficialElectionDivision', function ($q) use ($pollingBooth) {
                $q->where('idelection_division', PollingBooth::find($pollingBooth)->idelection_division)->where('allChild', 1);
            })->orWhereHas('beneficialDistrict', function ($q) use ($pollingBooth) {
                $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($electionDivision != null) {

            $questions = Analysis::with(['category'])->where('iddistrict', Auth::user()->office->iddistrict)->where('idoffice', Auth::user()->idoffice)->whereIn('idsub_category', [1, 2, 3])->where('idelection_division', $electionDivision)->get()->groupBy('idcategory');

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->
            whereHas('beneficialElectionDivision', function ($q) use ($electionDivision) {
                $q->where('idelection_division', $electionDivision);
            })->orWhereHas('beneficialDistrict', function ($q) use ($electionDivision) {
                $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else {
            $questions = Analysis::with(['category'])->where('iddistrict', Auth::user()->office->iddistrict)->where('idoffice', Auth::user()->idoffice)->whereIn('idsub_category', [1, 2, 3])->get()->groupBy('idcategory');
            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->get();
        }

        return response()->json(['success' => $questions, 'posts' => $posts]);

    }

    public function categoryWiseChart(Request $request)
    {
        $category = $request['category'];
        $village = $request['village'];
        $gramasewaDivision = $request['gramasewaDivision'];
        $pollingBooth = $request['pollingBooth'];
        $electionDivision = $request['electionDivision'];
        $district = Auth::user()->office->iddistrict;

        if ($category == null) {
            return response()->json(['errors' => ['error' => 'Please choose a category.']]);

        }

        if ($village != null) {

            $questions = Analysis::with(['category'])->where('idoffice', Auth::user()->idoffice)->where('idcategory', $category)->where('iddistrict', Auth::user()->office->iddistrict)->where('idvillage', $village)->where('idgramasewa_division', $gramasewaDivision)->get();

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->where(function ($q) use ($village) {
                $q->whereHas('beneficialVillage', function ($q) use ($village) {
                    $q->where('idvillage', $village);
                })->orWhereHas('beneficialGramasewaDivision', function ($q) use ($village) {
                    $q->where('idgramasewa_division', Village::find($village)->idgramasewa_division)->where('allChild', 1);
                })->orWhereHas('beneficialPollingBooth', function ($q) use ($village) {
                    $q->where('idpolling_booth', GramasewaDivision::find(Village::find($village)->idgramasewa_division)->idpolling_booth)->where('allChild', 1);
                })->orWhereHas('beneficialDistrict', function ($q) {
                    $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
                });
            })->where(function ($q) use ($category) {
                $q->whereHas('beneficialCategory', function ($q) use ($category) {
                    $q->where('idcategory', $category);
                });
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($gramasewaDivision != null) {

            $questions = Analysis::with(['category'])->where('idoffice', Auth::user()->idoffice)->where('idcategory', $category)->where('iddistrict', Auth::user()->office->iddistrict)->where('idgramasewa_division', $gramasewaDivision)->get();

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->where(function ($q) use ($gramasewaDivision) {
                $q->whereHas('beneficialGramasewaDivision', function ($q) use ($gramasewaDivision) {
                    $q->where('idgramasewa_division', $gramasewaDivision);
                })->orWhereHas('beneficialPollingBooth', function ($q) use ($gramasewaDivision) {
                    $q->where('idpolling_booth', GramasewaDivision::find($gramasewaDivision)->idpolling_booth)->where('allChild', 1);
                })->orWhereHas('beneficialElectionDivision', function ($q) use ($gramasewaDivision) {
                    $q->where('idelection_division', ElectionDivision::find(GramasewaDivision::find($gramasewaDivision)->idpolling_booth)->idelection_division)->where('allChild', 1);
                })->orWhereHas('beneficialDistrict', function ($q) {
                    $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
                });
            })->where(function ($q) use ($category) {
                $q->whereHas('beneficialCategory', function ($q) use ($category) {
                    $q->where('idcategory', $category);
                });
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($pollingBooth != null) {

            $questions = Analysis::with(['category'])->where('idoffice', Auth::user()->idoffice)->where('idcategory', $category)->where('iddistrict', Auth::user()->office->iddistrict)->where('idpolling_booth', $pollingBooth)->get();

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->where(function ($q) use ($pollingBooth) {
                $q->whereHas('beneficialPollingBooth', function ($q) use ($pollingBooth) {
                    $q->where('idpolling_booth', $pollingBooth);
                })->orWhereHas('beneficialElectionDivision', function ($q) use ($pollingBooth) {
                    $q->where('idelection_division', PollingBooth::find($pollingBooth)->idelection_division)->where('allChild', 1);
                })->orWhereHas('beneficialDistrict', function ($q) use ($pollingBooth) {
                    $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
                });
            })->where(function ($q) use ($category) {
                $q->whereHas('beneficialCategory', function ($q) use ($category) {
                    $q->where('idcategory', $category);
                });
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($electionDivision != null) {

            $questions = Analysis::with(['category'])->where('idoffice', Auth::user()->idoffice)->where('idcategory', $category)->where('iddistrict', Auth::user()->office->iddistrict)->where('idelection_division', $electionDivision)->get();

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->where(function ($q) use ($electionDivision) {
                $q->whereHas('beneficialElectionDivision', function ($q) use ($electionDivision) {
                    $q->where('idelection_division', $electionDivision);
                })->orWhereHas('beneficialDistrict', function ($q) use ($electionDivision) {
                    $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
                });
            })->where(function ($q) use ($category) {
                $q->whereHas('beneficialCategory', function ($q) use ($category) {
                    $q->where('idcategory', $category);
                });
            })->where('idoffice', Auth::user()->idoffice)->get();

        } else {
            $questions = Analysis::with(['category'])->where('idoffice', Auth::user()->idoffice)->where('idcategory', $category)->where('iddistrict', Auth::user()->office->iddistrict)->where('iddistrict', Auth::user()->office->iddistrict)->get();
            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->whereHas('beneficialDistrict', function ($q) use ($district) {
                $q->where('iddistrict', $district);
            })->whereHas('beneficialCategory', function ($q) use ($category) {
                $q->where('idcategory', $category);
            })->where('idoffice', Auth::user()->idoffice)->get();
        }

        return response()->json(['success' => $questions, 'posts' => $posts]);
    }

    public function getCategoryData(Request $request){
        $validationMessages = [
            'id.required' => 'Process invalid.Please refresh page and try again!',
            'level.required' => 'Process invalid.Please refresh page and try again!',
            'id.numeric' => 'Process invalid.Please refresh page and try again!',
            'level.numeric' => 'Process invalid.Please refresh page and try again!',
            'category.numeric' => 'Process invalid.Please refresh page and try again!',
            'category.numeric' => 'Process invalid.Please refresh page and try again!',
        ];
        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',
            'level' => 'required|numeric',
            'category' => 'required|numeric',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $id  = $request['id'];
        $level = $request['level'];
        $category = $request['category'];

        if($level == 2){
            $questions = Analysis::with(['pollingBooth'])->whereHas('category', function ($q) use ($category) {
                $q->where('idcategory', $category);
            })->where('iddistrict', Auth::user()->office->iddistrict)->where('idpolling_booth', $id)->where('idoffice', Auth::user()->idoffice)->whereIn('idsub_category', [1, 2, 3])->get()->groupBy('idgramasewa_division');

            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->where(function ($q) use ($id) {
                $q->whereHas('beneficialPollingBooth', function ($q) use ($id) {
                    $q->where('idpolling_booth', $id);
                })->orWhereHas('beneficialElectionDivision', function ($q) use ($id) {
                    $q->where('idelection_division', PollingBooth::find($id)->idelection_division)->where('allChild', 1);
                })->orWhereHas('beneficialDistrict', function ($q) use ($id) {
                    $q->where('iddistrict', Auth::user()->office->iddistrict)->where('allChild', 1);
                });
            })->where(function ($q) use ($category) {
                $q->whereHas('beneficialCategory', function ($q) use ($category) {
                    $q->where('idcategory', $category);
                });
            })->where('idoffice', Auth::user()->idoffice)->get();

        }
        else{
            $questions = Analysis::with(['pollingBooth'])->whereHas('category', function ($q) use ($category) {
                $q->where('idcategory', $category);
            })->where('iddistrict', Auth::user()->office->iddistrict)->where('idoffice', Auth::user()->idoffice)->whereIn('idsub_category', [1, 2, 3])->get()->groupBy('idpolling_booth');
            $posts = Post::with(['beneficialCategory', 'beneficialCategory.category'])->whereHas('beneficialCategory', function ($q) use ($category) {
                $q->where('idcategory', $category);
            })->get();
        }

        return response()->json(['success' => 'success','posts'=>$posts,'questions'=>$questions]);

    }
}
