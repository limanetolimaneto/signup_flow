<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignupsController extends Controller
{
    public function confirm(){
        $teste = 'testado!';
        return view("welcome",['teste'=>$teste]);
    }
}
