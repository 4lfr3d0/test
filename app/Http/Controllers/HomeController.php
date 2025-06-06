<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        try {
            return view('home');
        } catch (\Exception $error) {
            \Log::error('Error en el HomeController -> index(): ' . $error->getMessage());
            abort(500, 'Error al mostrar la página');
        }
    }

    
}
