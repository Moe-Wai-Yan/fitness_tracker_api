<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index(){
        $categories=Category::latest()->get();
        return response()->json([
            'category'=>$categories,
            'status'=>true,
            'message'=>'Category fetching successful'
        ]);
    }
    public function store(CategoryRequest $request){

       try {
       $category=Category::create([
        'name'=>$request->name
       ]);

        return response()->json([
            'category'=>$category,
            'status'=>true,
            'message'=>'Category created successfully!'
        ],201);
       } catch (\Exception $e) {
        return response()->json([
            'error' => 'Something went wrong while creating the category.',
            'details' => $e->getMessage(),
        ],500);
       }

    }

    public function show(Category $category){
      try {
        return response()->json([
            'category'=>$category,
            'status'=>true,
            'message'=>'success'

        ],201);
      } catch (\Exception $e) {
      return response()->json([
        'error'=>'Something went wrong',
        'details'=>$e->getMessage()
      ],500);
      }
    }

    public function destroy(Category $category){
        try{
            $category->delete();
            return response()->json([
                'message'=>'category deleted successful',
                'status'=>true,

            ],204);
        }catch(\Exception $e){
            return response()->json([
                'error'=>'something went wrong',
                'details'=>$e->getMessage()
            ],500);
        }
    }

    public function update(CategoryRequest $request,Category $category){
        try {
       $category->name=$request->name;
       $category->save();

           return response()->json([
            'message'=>'Category update successful',
            'status'=>true,
            'category'=>$category
           ],200);

        } catch (\Exception $e) {
            return response()->json([
                'error'=>'something went wrong',
                'details'=>$e->getMessage()
            ],500);
        }
    }
}
