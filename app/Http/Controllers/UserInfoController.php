<?php

namespace App\Http\Controllers;

use App\Models\ResponseMsg;
use App\Models\UserInfo;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserInfoController extends Controller
{
    public $table = "public.UserInfo";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('crud');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $query =  DB::table($this->table)->insert([
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'avatar' => $request->input('avatar'),
            'gender' => $request->input('gender') == 'on',
            'date_of_birth' => $request->input('dateOfBirth'),
            'receive_notify_email' => $request->input('receiveNotify') == 'on',
            'role' => $request->input('roleID'),
        ]);

        if ($query) {
            $response = new ResponseMsg("201", "Created", $request->input());
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("503", "Service Unavailable", null);
            return response()->json(($response));
        }
    }

    public function getUser(Request $request)
    {
        $query = DB::table($this->table)->where('user_id', '=', $request->input('user_id'))->get();
        if ($query) {
            $response = new ResponseMsg("200", "getUser", $query);
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("204", "No Content", null);
            return response()->json(($response));
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $query = DB::table($this->table)->where('user_id', '=', $request->input('user_id'))->update([
            'name' => $request->input('name'),
            // 'email' => $request->input('email'),
            'avatar' => $request->input('avatar'),
            'gender' => $request->input('gender') == 'on',
            'date_of_birth' => $request->input('dateOfBirth'),
            'receive_notify_email' => $request->input('receiveNotify') == 'on',
            'role' => $request->input('roleID'),
        ]);

        if ($query) {
            $response = new ResponseMsg("200", "Updated", $query);
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("503", "User is not exist", null);
            return response()->json(($response));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        //
        $query = DB::table($this->table)->where('user_id', '=', $request->input('user_id'))->delete();
        if ($query) {
            $response = new ResponseMsg("200", "Deleted", $query);
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("503", "User is not exist", null);
            return response()->json(($response));
        }
    }
    /**
     * Store a newly created resource in storage.
     */
}
