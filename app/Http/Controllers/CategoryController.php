<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function store(){

       try {
       $validator =Validator::make(request()->all(),[
        'name'=>'required|string|max:255'
       ],[
        'name.required'=>'Category name is required',
        'name.string'=>'Category name must be string'

       ]);
       if ($validator->fails()) {
        return response()->json([
            'errors'=> $validator->errors(),

        ],422);
       }

       $category=Category::create([
        'name'=>request('name')
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
}
