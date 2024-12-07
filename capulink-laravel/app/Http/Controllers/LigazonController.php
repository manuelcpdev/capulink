<?php

namespace App\Http\Controllers;

use App\Models\Ligazon;
use App\Models\RegexLigazonProhibida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LigazonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
        // Obtemos as expresións regulares prohibidas
        $regexProhibidas = DB::table('regex_ligazons_prohibdas')->pluck('regex')->toArray();

        // Comprobamos se a ligazón coincide cunha expresión regular prohibida
        foreach ($regexProhibidas as $regex) {
            if (preg_match("/$regex/", $request->url)) {
                return response()->json(['error' => "A ligazón '{$request->url}' está prohibida."], 400);
            }
        }
        */
    }

    /**
     * Display the specified resource.
     */
    public function show(Ligazon $ligazon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ligazon $ligazon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ligazon $ligazon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ligazon $ligazon)
    {
        //
    }
}
