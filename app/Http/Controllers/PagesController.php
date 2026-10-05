<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    /**
     * About Us page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Contributor Guidelines page.
     */
    public function contributorGuidelines()
    {
        return view('pages.contributor-guidelines');
    }
}
