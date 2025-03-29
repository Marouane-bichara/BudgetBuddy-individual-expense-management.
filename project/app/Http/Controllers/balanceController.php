<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;

class balanceController extends Controller
{
    //

    public function calculate($groupId)
    {

        
        $expenses = Expenses::where('groupe_id', $groupId)->get();

  
        $total = 0;
        foreach ($expenses as $expense) {
            $total += $expense->amount;
        }



        $users = Expenses::where('group_id', $groupId)->select('user_id')->distinct()->get();
        $usersBalances = [];
        foreach ($users as $user) {
            $userExpenses = Expenses::where('group_id', $groupId)->where('user_id', $user->user_id)->get();
            $userTotal = 0;
            foreach ($userExpenses as $expense) {
                $userTotal += $expense->amount;
            }
            $usersBalances[] = [
                'user_id' => $user->user_id,
                'balance' => $userTotal - ($total / count($users))
            ];
        }

        return response()->json([
            'total' => $total,
            'users_balances' => $usersBalances
        ]);
    }
}
