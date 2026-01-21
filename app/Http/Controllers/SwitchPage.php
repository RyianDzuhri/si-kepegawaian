<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class SwitchPage extends Controller
{
    public function showPage(): View
    {
        return view('layout.master');
    }
}
