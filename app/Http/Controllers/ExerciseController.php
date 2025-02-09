<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseRequest;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(){
       try {
        $exercises=Exercise::where('user_id',auth()->user()->id)
        ->filter(request()->only('search'))
        ->with('category')
        ->latest()
        ->paginate(5);
        return response()->json([
            'status'=>true,
            'exercises'=>$exercises,
            'message'=>'fetching exercises successful'
        ]);
       } catch (\Exception $e) {
       return response()->json([
        'details'=>'something went wrong',
        'errors'=>$e->getMessage()
       ],500);
       }

    }

    public function store(ExerciseRequest $request){
        try{
            $exercise=Exercise::create([
                'name'=>$request->name,
                'user_id'=>auth()->user()->id,
                'category_id'=>$request->category_id,
                 'duration'=>$request->duration,
                 'weight'=>$request->weight,
                'reps'=>$request->reps,
                'goal'=>$request->goal,
            ]);

            return response()->json([
                'message'=>'Exercise created successful',
                'status'=>true,
                'exercise'=>$exercise
            ],201);

        }catch(\Exception $e){
            return response()->json([
                'message'=>'something went wrong',
                'errors'=>$e->getMessage(),
            ],500);
        }
    }

    public function show(Exercise $exercise){
        try {
            $exercise=$exercise->with('category')->firstOrFail();
            return response()->json([
                'exercise'=>$exercise,
                'message'=>'success',
                'status'=>true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'something went wrong',
                'errors'=>$e->getMessage()
            ],500);
        }
    }

    public function destroy(Exercise $exercise){
        try {
            $exercise->delete();
            return response()->json([
                'message'=>'successfully deleted',
                'status'=>true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'something went wrong',
                'errors'=>$e->getMessage()
            ],500);
        }
    }

    public function update(ExerciseRequest $request , Exercise $exercise){
        try {
            $exercise->update([
                'name'=>$request->name,
                'user_id'=>auth()->user()->id,
                'duration'=>$request->duration,
                'weight'=>$request->weight,
                'reps'=>$request->reps,
                'goal'=>$request->goal
            ]);

            return response()->json([
                'message'=>'update successful',
                'exercise'=>$exercise,
                'status'=>true
            ],200);
        } catch (\Exception $e) {
           return response()->json([
            'errors'=>$e->getMessage(),
            'message'=>'something went wrong'
           ],500);
        }
    }
}
