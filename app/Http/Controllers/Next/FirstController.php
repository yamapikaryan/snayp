<?php


namespace App\Http\Controllers\Next;


use App\RoleHasUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class FirstController
{
    public function index(Request $request)
    {

//        if (isset($_GET['generate_users'])) {
//            for ($i = 0; $i < 10; $i++) {
//                $currentUserData = [
//                    'email' => Str::random(10) . '@gmail.com',
//                    'password' => Str::random(10),
//                ];
//
//                $currentUser = User::forceCreate([
//                    'name' => Str::random(10),
//                    'email' => $currentUserData['email'],
//                    'password' => Hash::make($currentUserData['password']),
//                ]);
//
//                RoleHasUser::forceCreate([
//                    'role_id' => 2,
//                    'user_id' => $currentUser->id,
//                ]);
//
//                echo 'New user: ' . $currentUserData['email'] . ' , password: ' . $currentUserData['password'] . "<br>\n";
//            }
//
//            die;
//        }

        $params = $request->all();

        if($request->ajax()){
            switch($params['type'] ?? 0){
                // инициализируем компонент
                case 1:

                    $managers = User::whereHas('roles', function ($query) {
                        $query->where(['roles.id' => 2]);
                    })->get();

                    return ['managers' => $managers];

                    break;
            }
        }


        return view('next.index');
    }
}
