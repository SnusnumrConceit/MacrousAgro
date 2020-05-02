<?php

namespace App\Exports;

use App\Exports\Sheets\UserSheet;
use Kodeine\Acl\Models\Eloquent\Role;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersExport implements Responsable, WithMultipleSheets
{
    use Exportable;

    private $fileName = 'users.xlsx';

    public function sheets(): array
    {
        $sheets = [];

        $headers = Role::all('name')->pluck('name')->toArray();

        foreach ($headers as $header) {
            $sheets[] = new UserSheet($header);
        }

        return $sheets;
    }
}
