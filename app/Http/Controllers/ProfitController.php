<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfitController extends Controller
{
    public function index(Request $request): Response
    {
        $profits = Profit::all();

        return view('profit.index', [
            'profits' => $profits,
        ]);
    }
}
