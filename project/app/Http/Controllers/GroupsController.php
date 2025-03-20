<?php

namespace App\Http\Controllers;
use App\Models\Groups;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $groups = Groups::with('users')->where('created_by', auth()->id())->get();

        return response()->json([
            'groups' => $groups
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
        //

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'users'=> 'required|array',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $group = Groups::create([
            'name' => $request->name,
            'currency' => $request->currency,
            'created_by' => auth()->user()->id,
        ]);

        $groupUsers = [];

        foreach ($request->users as $userId) {
            $groupUsers[] = GroupUser::create([
                'user_id' => $userId,
                'group_id' => $group->id,
            ]);
        }

        return response()->json([
            'group' => $group,
            'users' => $groupUsers,
            'message' => 'Group created successfully'
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
        //

        $group = Groups::with('users')->find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        return response()->json([
            'group' => $group
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
        //

        $group = Groups::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'users'=> 'required|array',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $group->update($request->only(['name', 'currency']));
        $groupUsers = [];

        if ($request->has('users')) {
            $group->users()->delete();

            foreach ($request->users as $userId) {
                $groupUsers[] = GroupUser::create([
                    'user_id' => $userId,
                    'group_id' => $group->id,
                ]);
            }
        }

        return response()->json([
            'group' => $group,
            'users' => $groupUsers,
            'message' => 'Group updated successfully'
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
        //

        $group = Groups::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }


        $group->users()->delete();
        $group->delete();

        return response()->json([
            'message' => 'Group deleted successfully'
        ]);
    }
}
