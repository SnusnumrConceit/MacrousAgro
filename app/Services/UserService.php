<?php
declare(strict_types=1);


namespace App\Services;

use App\Exports\UsersExport;

class UserService
{
    public function export()
    {
        return new UsersExport;
    }
}
