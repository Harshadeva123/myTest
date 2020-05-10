<?php

namespace App\Http\Controllers;

use App\Category;
use App\MainCategory;
use App\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *render 'add category' interface
     */
    public function index()
    {
        $mainCats = MainCategory::where('status', '1')->get();

        return view('category.add_category', ['title' =>  __('Add Category'), 'mainCats' => $mainCats]);
    }

    public function getSubCatByMain(Request $request){
        $id = $request['id'];
        $subCats  = SubCategory::where('idmain_category',$id)->where('status',1)->get();
        if($subCats != null){
            return response()->json(['success' => $subCats]);
        }
        else{
            return response()->json(['errors' => ['subCat'=>'Sub Categories not found!']]);

        }
    }

    public function getCatBySub(Request $request){
        $id = $request['id'];
        $categories  = Category::where('idsub_category',$id)->where('status',1)->get();
        if($categories != null){
            return response()->json(['success' => $categories]);
        }
        else{
            return response()->json(['errors' => ['newCat'=>'Categories not found!']]);

        }
    }

    /**
     *add new category to database
     */
    public function store(Request $request){

        //validation start
        $validator = \Validator::make($request->all(), [
            'subCat' => 'required|exists:sub_category,idsub_category',
            'newCat' => 'required|max:255',

        ], [
            'subCat.required' => 'Sub category should be provided!',
            'subCat.exists' => 'Sub category invalid!',
            'newCat.required' => 'New category name should be provided!',
            'newCat.max' => 'New category should be less than 255 characters long!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $exist = Category::where('idsub_category',$request['subCat'])->where('category',$request['category'])->first();
        if($exist != null){
            return response()->json(['errors' => ['newCat'=>'Categories already exist!']]);

        }
        //validation end


        //save in category table
        $category = new Category();
        $category->idsub_category = $request['subCat'];
        $category->category = $request['newCat'];
        $category->status = 1;//default value for active categories
        $category->save();
        //save in category table  end


        return response()->json(['success' => 'Category Saved Successfully!']);

    }

    public function view(Request $request){
        $categories = Category::where('status', '1')->paginate(10);
        $subCategories = SubCategory::where('status', '1')->get();
        $mainCategories = MainCategory::where('status', '1')->get();

        return view('category.view_category', ['title' =>  __('View Category'), 'categories' => $categories,'subCategories'=>$subCategories,'mainCategories'=>$mainCategories]);
    }
}
