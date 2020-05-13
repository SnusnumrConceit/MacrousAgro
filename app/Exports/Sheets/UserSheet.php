<?php

namespace App\Exports\Sheets;

use App\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
//use Maatwebsite\Excel\Concerns\FromQuery;

class UserSheet implements WithTitle, FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function title(): string
    {
        return ucfirst(Str::plural($this->role));
    }

    public function map($user) : array
    {
        return [
            $user->full_name,
            $user->email,
            $user->birthday->format('d.m.Y')
        ];
    }

    public function headings(): array
    {
        return [
            __('users.export.headings.full_name'),
            __('users.export.headings.email'),
            __('users.export.headings.birthday')
        ];
    }

    public function collection()
    {
        return User::whereHas('roles', function ($q) {
            return $q->where('name', $this->role);
        })->orderBy('last_name')->orderBy('first_name')->get();
    }
}
