<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'category' => Catalog::category('martillos-hidraulicos'),
            'products' => Catalog::products('martillos-hidraulicos'),
        ]);
    }
}
