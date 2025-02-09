<?php

namespace Modules\Dashboard\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pageTitle = 'Trang Quản Trị';
        return view('dashboard::dashboard', compact('pageTitle'));
    }
}
