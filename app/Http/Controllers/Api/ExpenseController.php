<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/expenses",
     *     summary="Get all expenses",
     *     description="Get all expenses",
     *     tags={"Expenses"},
     *     @OA\Response(response = "200", description="All expenses"),
     *     @OA\Response(response = "404", description="No expenses found"),
     *     @OA\SecurityScheme(
     *         type="apiKey",
     *         in="header",
     *         securityScheme="sanctum",
     *         name="Authorization"
     *     ),
     *     security={{"sanctum": {}}},
     * )
     */

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
    
    /**
     * @OA\Post(
     *   path="/api/expenses",
     *   summary="Create a new expense",
     *   description="Create a new expense",
     *   tags={"Expenses"},
     *   @OA\RequestBody(
     *      required=true,
     *    @OA\JsonContent(
     *     required={"description","amount","category"},
     *    @OA\Property(property="description", type="string", format="string", example="Electricity Bill"),
     *    @OA\Property(property="amount", type="integer", format="integer", example="5000"),
     *    @OA\Property(property="category", type="string", format="string", example="Utilities"),
     *   ),
     *   ),
     *   @OA\Response(response="201", description="Expense created successfully"),
     *   @OA\Response(response="422", description="Validation errors"),
     *   @OA\Response(response="500", description="Expense not created"),
     *   @OA\SecurityScheme(
     *         type="apiKey",
     *         in="header",
     *         securityScheme="sanctum",
     *         name="Authorization"
     *     ),
     *     security={{"sanctum": {}}},
     *
     * )
     * 
     */
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

    /**
     * @OA\Get(
     *    path="/api/expenses/{id}",
     *    summary="Get a single expense",
     *    description="Get a single expense",
     *    tags={"Expenses"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID of the expense",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="200", description="Expense found"),
     *   @OA\Response(response="404", description="Expense not found"),
     *   @OA\SecurityScheme(
     *   type="apiKey",
     *   in="header",
     *   securityScheme="sanctum",
     *   name="Authorization"
     *   ),
     *   security={{"sanctum": {}}},
     * )
     */
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

    /**
     * @OA\Put(
     *   path="/api/expenses/{id}",
     *   summary="Update an expense",
     *   description="Update an expense",
     *   tags={"Expenses"},
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    description="ID of the expense",
     *    required=true,
     *   @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     *   required=true,
     * @OA\JsonContent(
     * required={"description","amount","category"},
     * @OA\Property(property="description", type="string", format="string", example="Electricity Bill"),
     * @OA\Property(property="amount", type="integer", format="integer", example="5000"),
     * @OA\Property(property="category", type="string", format="string", example="Utilities"),
     * ),
     * ),
     * @OA\Response(response="200", description="Expense updated successfully"),
     * @OA\Response(response="422", description="Validation errors"),
     * @OA\Response(response="403", description="Expense not found"),
     * @OA\SecurityScheme(
     * type="apiKey",
     * in="header",
     * securityScheme="sanctum",
     * name="Authorization"
     * ),
     * security={{"sanctum": {}}},
     * )
     */ 
     
    public function update(Request $request, $id){
        
            $expense = Expense::find($id);

            if(!$expense){
                return response()->json([
                    'message' => 'Expense not found',
                ] , 403);
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

    /**
     * @OA\Delete(
     *  path="/api/expenses/{id}",
     *  summary="Delete an expense",
     *  description="Delete an expense",
     *  tags={"Expenses"},
     *  @OA\Parameter(
     *   name="id",
     *   in="path",
     *   description="ID of the expense",
     *   required=true,
     *   @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(response="200", description="Expense deleted successfully"),
     *  @OA\Response(response="404", description="Expense not found"),
     *  @OA\SecurityScheme(
     *  type="apiKey",
     *  in="header",
     *  securityScheme="sanctum",
     *  name="Authorization"
     *  ),
     *  security={{"sanctum": {}}},
     * )
     */
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
