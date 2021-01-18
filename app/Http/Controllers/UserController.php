<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{CreateCompanyLogin, CreatePersonLogin};
use App\Http\Controllers\Controller;
use App\Models\{User, Company, Person};
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }



    public function index(Request $request){
        $user_data = ($request->user()->getIndex())->first();
        $return_data = [];

        if($user_data->type == 'company'){
            $return_data['company'] = $user_data;

        }

        if($user_data->type == 'person'){
            $return_data['person'] = $user_data;
            $return_data['trails'] = \DB::table('trails')
            ->select(
                [
                    \DB::raw('trails.id as trail_id'),
                    'trails.name','trails.description',
                    \DB::raw('count(courses.id) as course_total'),
                    \DB::raw('concat("[",GROUP_CONCAT("{\"course_id\":\"", courses.id,"\",\"course_name\":\"", courses.name,"\",\"course_description\":\"", courses.description,"\"}"),"]") as courses')
                ])
            ->leftJoin('courses','courses.trail_id','trails.id')
            ->groupBy('trails.id','trails.name','trails.description')
            ->get();
            foreach($return_data['trails'] as &$trail){
                $trail->courses = json_decode($trail->courses,true);
            }
            $return_data['courses_status'] = \DB::table('lesson_person')
            ->join('lessons','lessons.id','lesson_person.lesson_id')
            ->join('courses','lessons.course_id','courses.id')
            ->join('trails','trails.id','courses.trail_id')
            ->groupBy('lessons.course_id')
            ->groupBy('trails.id')
            ->select([\DB::raw('max(lessons.id) lesson_id'),'lessons.course_id',\DB::raw('trails.id as trail_id')])
            ->where('lesson_person.person_id',$user_data->person_id)->get();

            $return_data['courses_completed'] = \DB::table('person_test_tries')
            ->select([\DB::raw("'pass' as status"),'tests.course_id'])
            ->join('tests','person_test_tries.test_id','tests.id')
            ->where('person_test_tries.person_id',$user_data->person_id)->get();

        }
        \Log::error(print_r($return_data,true));
        return response()->json($return_data , $this->successStatus);
    }

    public function registerCompany(CreateCompanyLogin $request){
        $user_data = $this->register($request);
        $company_data = array_merge($request->except(['email','name']),['user_id' => $user_data['user']->id]);
        Company::create($company_data);
        return response()->json(['success'=>$user_data['token']], $this->successStatus);
    }

    public function registerPerson(CreatePersonLogin $request){
        $user_data = $this->register($request);
        $person_data = array_merge($request->except(['email','name']),['user_id' => $user_data['user']->id]);
        Person::create($person_data);
        return response()->json(['success'=>$user_data['token']], $this->successStatus);
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    private function register(Request $request)
    {

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        return ['user' => $user, 'token' => $success];
    }


    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
