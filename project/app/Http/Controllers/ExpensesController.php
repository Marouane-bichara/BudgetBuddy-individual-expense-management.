<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\Expenses;
use App\Models\ExpensesTags;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expenses::with('tags')->where('user_id', auth()->id())->get();
    
        return response()->json([
            'expenses' => $expenses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
            'tags' => 'required|array',
        ]);
    
        $expense = Expenses::create([
            'user_id' => auth()->id(), 
            'amount' => $validate['amount'],
            'description' => $validate['description'], 
            'date' => now(),
        ]);
    
        $tagIds = [];
        foreach ($validate['tags'] as $tagName) {
            $tag = Tags::firstOrCreate(['name' => $tagName]); 
            $tagIds[] = $tag->id;
        }
    
        $expense->tags()->attach($tagIds);
    
        return response()->json([
            'expense' => $expense->load('tags'), 
            'message' => 'Expense created successfully'
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expenses::with('tags')->where('user_id', auth()->id())->find($id);
    
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
    
        return response()->json([
            'expense' => $expense
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $expense = Expenses::where('user_id', auth()->id())->find($id);

    if (!$expense) {
        return response()->json(['message' => 'Expense not found'], 404);
    }

    $validate = $request->validate([
        'amount' => 'sometimes|numeric',
        'description' => 'sometimes|string|max:255',
        'tags' => 'sometimes|array',
    ]);

    $expense->update($validate);

    if ($request->has('tags')) {
        $tagIds = [];
        foreach ($validate['tags'] as $tagName) {
            $tag = Tags::firstOrCreate(['name' => $tagName]); 
            $tagIds[] = $tag->id;
        }
        
        
        $expense->tags()->sync($tagIds); 
    }

    return response()->json([
        'expense' => $expense->load('tags'),
        'message' => 'Expense updated successfully'
    ]);
}

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expenses::where('user_id', auth()->id())->find($id);
    
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
    
        $expense->tags()->detach();
    
        $expense->delete();
    
        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
