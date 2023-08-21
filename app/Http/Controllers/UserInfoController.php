<?php

namespace App\Http\Controllers;

use App\Models\ResponseMsg;
use App\Models\UserInfo;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;

class UserInfoController extends Controller
{
    public $table = "public.UserInfo";
    // Elasticsearch PHP client
    protected $elasticsearch;
    // Elastica client
    protected $elastica;
    // Elastica index
    protected $elasticIndex;    
    
    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()
            ->setHosts(config('database.connections.elasticsearch.hosts'))
            ->setBasicAuthentication('oMbvJYsWP4', 'c7CjNfwRXGiz2xDUt65dBg')
            ->build();
    }

    public function searchUserByName(Request $request)
    {
        try {

            if ($request->input('name')) {
                $name = $request->input('name');
            } else {
                return response()->json([
                    'message' => 'Please enter name to search'
                ], 400);
            }

            $offset = $request->input('name') != "" ? max(0, intval($request->input('name'))) : 0;
            $limit = $request->input('limit') != "" ? max(1, intval($request->input('limit'))) : 10;
            
            $params = [
                'index' => 'users',
                'body' => [
                    'query' => [
                        'match' => [
                            'name' => $name
                        ]
                    ]
                ],
                'from' => $offset,
                'size' => $limit
            ];
            
            $response = $this->elasticsearch->search($params);
            $hits = $response['hits']['hits'];
            $users = [];
            foreach ($hits as $hit) {
                $users[] = $hit['_source'];
            }
            return response()->json([
                'users' => $users,
                'total' => $response['hits']['total']['value'],
                'message' => 'Search successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('crud');
    }

    public function test(Request $request)
    {
        echo ($request->flash());

        // print_r($request->all());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $isExist = DB::table($this->table)->where('email', $request->input('email'))->exists();
        if ($isExist != null){
            $response = new ResponseMsg("503", "Service Unavailable", "Email is existed");
            return response()->json(($response));
        }

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

    public function register(Request $request)
    {
        $query =  DB::table($this->table)->insert([
            'user_id' =>$request->has('user_id') ? $request->input('user_id') : '',
            'name' =>$request->has('name')? $request->input('name'): '',
            'about' =>$request->has('about')? $request->input('about'):'',
            'address' =>$request->has('address')? $request->input('address'):'',
            'email' =>$request->has('email')? $request->input('email'):'',
            'role' =>$request->has('role')? $request->input('role'):'',
            'device_token' => $request->has('device_token') ?  $request->input('device_token') : '',
        ]);

        if ($query) {
            $response = new ResponseMsg("201", "Created", $request->input());
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("503", "Service Unavailable", null);
            return response()->json(($response));
        }
    }

    public function getUsers(Request $request){
        $sUserIds =  $request->input('lstIDs');
        // return response()->json(($sUserIds));
        // explode(',', $sUserIds)
        $query = DB::table($this->table)->whereIn('user_id', $sUserIds)->get();
        if ($query) {
            $response = new ResponseMsg("200", "List User: ", $query);
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("204", "No Content", null);
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

    public function getUsersPaging(Request $request){
        $query = DB::table($this->table);
        if ($query) {
            if ($request->input('isAdmin') != 'on'){
                $query = $query->where('delete_at', '=', null);
            }

            if ($request->has('searchName') && $request->input('searchName') != ''){
                $query = $query->where('name', '=', $request->input('searchName'));
            }
            $response = new ResponseMsg("200", "User per page", $query->paginate(15));
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
            'date_of_birth' => $request->input('date_of_birth'),
            'receive_notify_email' => $request->input('receive_notify_email') == 'on',
            'role' => $request->input('role'),
            'device_token' => $request->has('device_token') ?  $request->input('device_token') : '',
            'about' => $request->has('about') ?  $request->input('about') : '',
            'address' => $request->has('address') ?  $request->input('address') : '',
        ]);

        if ($query) {
            $response = new ResponseMsg("200", "Updated", $query);
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("503", "User is not exist", null);
            return response()->json(($response));
        }
    }

    public function updateDevice_Token(Request $request){
        $query = DB::table($this->table)->where('user_id', '=', $request->input('user_id'))->update([
            'device_token' => $request->has('device_token') ?  $request->input('device_token') : '',
        ]);

        if ($query) {
            $response = new ResponseMsg("200", "Device Token Updated", $query);
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
        $query = DB::table($this->table)->where('user_id', '=', $request->input('user_id'))->update([
            'delete_at' => Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
        ]);
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
    
    
    // public function searchUser(Request $request){
    //     $query = DB::table($this->table)->where('name', '=', $request->input('name'))->get();
    //     if ($query) {
    //         $response = new ResponseMsg("200", "Search Result", $query);
    //         return response()->json(($response));
    //     } else {
    //         $response = new ResponseMsg("503", "User is not exist", null);
    //         return response()->json(($response));
    //     }
    // }

    public function getCount(Request $request){
            $query = DB::table($this->table);

        if ($query) {
            if ($request->input('isAdmin') != 'on'){
                $query = $query->where('delete_at', '=', null);
            }
            $response = new ResponseMsg("200", "Count", $query->count());
            return response()->json(($response));
        } else {
            $response = new ResponseMsg("503", "", null);
            return response()->json(($response));
        }
    }

    public function getLstReceiveEmail(Request $request){
        $query = DB::table($this->table)->where('receive_notify_email', '=', true)->where('role', '=', 'ADMIN')->get();

    if ($query) {
        $response = new ResponseMsg("200", "List users allow receive email", $query);
        return response()->json(($response));
    } else {
        $response = new ResponseMsg("503", "", null);
        return response()->json(($response));
    }
}
}