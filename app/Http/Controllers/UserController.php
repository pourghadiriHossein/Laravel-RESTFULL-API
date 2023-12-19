<?php

namespace App\Http\Controllers;

use App\Actions\SMS;
use App\Enum\Roles;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\OrderShipped;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //code for login
    public function getLoginCode($phone)
    {
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return $this->failResponse([
                'message' => 'User Not Found'
            ], 403);
        }

        $randomCode = Str::random(4);
        $user->verify_code = Hash::make($randomCode);
        $user->save();
        $state = SMS::sendSMS($user->phone, $user->name, $randomCode);
        if ($state) {
            return $this->successResponse([
                'message' => "Check Your Phone",
            ], 200);
        } elseif (!$state) {
            return $this->failResponse([
                'error' => "your request failed",
            ], 500);
        } else {
            return $this->failResponse([
                'error' => $state,
            ], 500);
        }
    }
    //create user
    public function register(CreateUserRequest $request)
    {
        $check = User::where('email', $request->input('email'))->first();
        if ($check)
            return $this->failResponse([
                'errors' => ['error' => ['This E-Mail Already Exist']],
            ]);
        $data = $request->safe(['name', 'phone', 'email', 'password']);
        $user = new User([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->save();
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        if ($request->file('avatar'))
            $this->storeUserAvatar($request->file('avatar'), $user->id);

        $data = [
            'username' => $user->name
        ];
        // first
        // Mail::send('mail.welcome', $data, function ($message) use ($user) {
        //     $message->to($user->email)
        //         ->subject("Welcome To Site");
        //     $message->from('fake.poulstar@gmail.com', 'Welcome');
        // });

        // second
        Mail::to($user->email)->send(new OrderShipped($user));


        if ($user) {
            return $this->successResponse([
                'errors' => ['error' => ['User Created']],
            ]);
        }
        return $this->failResponse();
    }
    //user profile
    public function profile()
    {
        $user = User::where('id', Auth::id())->with('roles')->with('media')->first();
        if ($user) {
            return $this->successResponse([
                'user' => $user,
                'message' => 'profile',
            ]);
        } else
            return $this->failResponse();
    }
    public function updateMyProfile(UpdateUserRequest $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return $this->failResponse([], 403);
        }
        if ($user->id < 11) {
            return $this->failResponse([
                'errors' => ['error' => ['You Can not Update this User']],
            ]);
        }
        $data = $request->safe(['name', 'phone', 'email', 'password']);

        if ($request->input('name'))
            $user->name = $data['name'];

        if ($request->input('phone') && $request->input('phone') != Auth::user()->phone) {
            if (User::where('phone', $request->input('phone'))->first()) {
                return $this->failResponse([
                    'errors' => ['error' => ['This Phone Already Exist']],
                ]);
            } else {
                $user->phone = $data['phone'];
            }
        }
        if ($request->input('email') && $request->input('email') != Auth::user()->email) {
            if (User::where('email', $request->input('email'))->first()) {
                return $this->failResponse([
                    'errors' => ['error' => ['This E-Mail Already Exist']],
                ]);
            } else {
                $user->email = $data['email'];
            }
        }
        if ($request->input('password'))
            $user->name = Hash::make($data['password']);
        $user->update();

        if ($request->file('avatar'))
            $this->storeUserAvatar($request->file('avatar'), $user->id);
        return $this->successResponse([
            'errors' => ['error' => ['User Updated']],
        ]);
    }
    //all users
    public function allUsers()
    {
        $filter = request()->input('filter');
        if (Auth::user()->getRoleNames()[0] !== Roles::ADMIN) {
            return $this->failResponse([], 403);
        }
        $query = User::query()
            ->select([
                'id',
                'name',
                'phone',
                'email'
            ])
            ->when($filter, function (Builder $limit, string $filter) {
                $limit->where(DB::raw('lower(name)'), 'like', '%' . strtolower($filter) . '%');
            })
            ->orderBy('id', 'desc')
            ->with('roles');

        $users = $query->paginate(5);
        return $this->paginatedSuccessResponse($users, 'users');
    }
    public function createUserByAdmin(CreateUserRequest $request)
    {
        if (Auth::user()->getRoleNames()[0] !== Roles::ADMIN) {
            return $this->failResponse([], 403);
        }
        $check = User::where('email', $request->input('email'))->first();
        if ($check)
            return $this->failResponse([
                'errors' => ['error' => ['This E-Mail Already Exist']],
            ]);
        $data = $request->safe(['name', 'phone', 'email', 'password']);
        $user = new User([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->save();
        if ($request->input('role') === 'admin') {
            $user->assignRole(Role::findByName(Roles::ADMIN, 'api'));
        } elseif ($request->input('role') === 'user') {
            $user->assignRole(Role::findByName(Roles::USER, 'api'));
        } else {
            $user->assignRole(Role::findByName(Roles::USER, 'api'));
        }
        if ($request->file('avatar'))
            $this->storeUserAvatar($request->file('avatar'), $user->id);
        if ($user) {
            return $this->successResponse([
                'errors' => ['error' => ['User Created']],
            ]);
        }
        return $this->failResponse();
    }
    public function updateUserByAdmin(UpdateUserRequest $request, User $user)
    {
        if (Auth::user()->getRoleNames()[0] !== Roles::ADMIN) {
            return $this->failResponse([], 403);
        }
        if ($user->id < 11) {
            return $this->failResponse([
                'errors' => ['error' => ['You Can not Update this User']],
            ]);
        }
        $data = $request->safe(['name', 'phone', 'email', 'password']);

        if ($request->input('name'))
            $user->name = $data['name'];

        if ($request->input('phone') && $request->input('phone') != Auth::user()->phone) {
            if (User::where('phone', $request->input('phone'))->first()) {
                return $this->failResponse([
                    'errors' => ['error' => ['This phone Already Exist']],
                ]);
            } else {
                $user->phone = $data['phone'];
            }
        }
        if ($request->input('email') && $request->input('email') != Auth::user()->email) {
            if (User::where('email', $request->input('email'))->first()) {
                return $this->failResponse([
                    'errors' => ['error' => ['This E-Mail Already Exist']],
                ]);
            } else {
                $user->email = $data['email'];
            }
        }
        if ($request->input('password'))
            $user->name = Hash::make($data['password']);
        $user->update();

        DB::table('model_has_roles')->where('model_id', $user->id)->delete();

        if ($request->input('role') === 'admin') {
            $user->assignRole(Role::findByName(Roles::ADMIN, 'api'));
        } elseif ($request->input('role') === 'user') {
            $user->assignRole(Role::findByName(Roles::USER, 'api'));
        } else {
            $user->assignRole(Role::findByName(Roles::USER, 'api'));
        }
        if ($request->file('avatar'))
            $this->storeUserAvatar($request->file('avatar'), $user->id);
        if ($user) {
            return $this->successResponse([
                'errors' => ['error' => ['User Updated']],
            ]);
        }
        return $this->failResponse();
    }
    public function deleteUserByAdmin(User $user)
    {
        if (Auth::user()->getRoleNames()[0] !== Roles::ADMIN) {
            return $this->failResponse([], 403);
        }
        if ($user->id < 11) {
            return $this->failResponse([
                'errors' => ['error' => ['You Can not Delete this User']],
            ]);
        }
        if (isset(($user->media)))
            $this->deleteMedia($user->media);
        if ($user->delete()) {
            return $this->successResponse([
                'errors' => ['error' => ['User Deleted']],
            ]);
        }
        return $this->failResponse();
    }
}
