<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index(){

        $expenses =Expense::all();

        if($expenses->isEmpty()){
            return response()->json([
                'message' => 'No expenses found',
            ], 404);
        }

        return response()-> json([
            'expenses' => $expenses,
            'status' => 'success',
        ], 200);
        
    }
    
    public function store(Request $request){

        $ExpenseValidator = Validator::make($request->all(),[
            'description' => 'required | string | max:25',
            'amount' => 'required| integer',
            'category' => 'required | string',
        ]);

        if($ExpenseValidator->fails()){
            return response()->json([
                'message' => $ExpenseValidator->errors(),
                'status' => 'error',
            ]);
        }

        $expense = Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'user_id' => $request->user()->id,
        ]);

        if(!$expense){
            return response()->json([
                'message' => 'Expense not created',
                'status' => 'error',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Expense created successfully',
            'expense' => $expense,
        ], 201);
    }

    public function show($id){

        $expense = Expense::find($id);

        if(!$expense){
            return response()->json([
                'message' => 'Expense not found',
            ] , 404);
        }

        return response()->json([
            'status' => 'success',
            'expense' => $expense,
        ] , 200);
    }

    public function update(Request $request, $id){
        
            $expense = Expense::find($id);

            if(!$expense){
                return response()->json([
                    'message' => 'Expense not found',
                ] , 404);
            }

            $ExpenseValidator = Validator::make($request->all(),[
                'description' => 'required | string | max:25',
                'amount' => 'required | integer',
                'category' => 'required | string',
            ]);

           if($ExpenseValidator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $ExpenseValidator->errors(),
                ] , 422);
           }

           $expense->update($request->all());

           return response()->json([
                'status' => 'success',
                'message' => 'Expense updated successfully',
                'expense' => $expense,

           ] , 200);
    }

    public function destroy($id){

        $expense = Expense::findOrFail($id);

        if(!$expense){
            return response()->json([
                'message' => 'Expense not found',
            ], 404);
        }

        $expense->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense deleted successfully',
        ], 200);
    }

}
