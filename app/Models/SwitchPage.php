<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class SwitchPage extends Model
{
    public function showPage(): View
    {
        return view('master');
    }
}
