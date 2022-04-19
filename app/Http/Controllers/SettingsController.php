<?php

namespace App\Http\Controllers;

use App\Company;
use App\Category;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //

    public function pro_module()
    {
        $categories = Category::all();
        $my_categories = auth()->user()->getAssocCategories();

        if(auth()->user()->company)
            $company = auth()->user()->company;
        else
            $company = new Company;

        return view('volgh.settings.pro_module', [
            'categories' => $categories,
            'my_categories' => $my_categories,
            'company'   => $company
        ]);
    }
}
