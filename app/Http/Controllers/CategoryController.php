<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(CategoryRequest $request){

       try {
        $category=Category::create($request->validated());

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
