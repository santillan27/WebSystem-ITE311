<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends BaseController
{
    /**
     * Default homepage
     */
    public function index()
    {
        $data = [
            'title' => 'Welcome to My Website'
        ];
        return view('index', $data);
    }

    /**
     * About page
     */
    public function about()
    {
        $data = [
            'title' => 'About Us'
        ];
        return view('about', $data);
    }

    /**
     * Contact page
     */
    public function contact()
    {
        $data = [
            'title' => 'Contact Us'
        ];
        return view('contact', $data);
    }
}
