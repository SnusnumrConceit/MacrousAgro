<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, Responsable, WithMapping
{
    use Exportable;

    private $fileName = 'users.xlsx';

    public function map($user) : array
    {
        return [
            $user->last_name,
            $user->first_name,
            $user->birthday,
            $user->email
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
}
