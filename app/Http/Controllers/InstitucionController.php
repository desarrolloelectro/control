<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Institucion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class InstitucionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }
    
    public function index()
    {
        if(Auth::user()->validar_permiso('con_abrir_conf')){
            $title = "Configuración";
            $controlador = "configuracion";
            return view('institucion.index', compact('title','controlador'));
        }else{
            return view('errors.access_denied');
        }
    }
}



