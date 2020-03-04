<?php
declare(strict_types=1);


namespace App\Services;


use App\Exports\UsersExport;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\User;
use App\Http\Resources\User\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    public function export(Request $request)
    {
//        return Excel::download(new UsersExport, 'users.xlsx');
        return new UsersExport();
    }
}
