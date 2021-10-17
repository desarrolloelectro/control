<?php

namespace App\Http\Controllers;
use App\Dsi;
//use App\DsiData;
use App\DsiMeta;
use App\Http\Requests\DsiMetaStoreRequest;
use Illuminate\Http\Request;

class DsiMetaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dsi_id)
    {
        $dsi = Dsi::find($dsi_id);
        $title = "Campos personalizados para ".$dsi->name;
        $dsi_metas = DsiMeta::where('dsi_id',$dsi_id)->where('parent','=','')->paginate();
        $types = DsiMeta::$types;
        $dsi_meta_parent = DsiMeta::where('dsi_id',$dsi_id)->where('parent','=','')->pluck('field_name','id');
        return view('dsi.meta.index', compact('dsi', 'title','dsi_metas', 'types', 'dsi_meta_parent'));
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function config($dsi_id)
    {
        dd("Meta config $dsi_id");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($dsi_id)
    {
        dd("create $dsi_id");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DsiMetaStoreRequest $request, $dsi_id)
    {
        $dsi_meta = New DsiMeta();
        $dsi_meta->dsi_id = $dsi_id;
        $dsi_meta->field_name = $request->field_name;
        $dsi_meta->type = $request->type;
        $dsi_meta->parent = $request->parent;
        $dsi_meta->parent_value = $request->parent_value;
        $dsi_meta->attribs = isset($request->attribs) ? json_encode($request->attribs) : "{}";
        if($request->type=='list'){
            $dsi_meta->options = (isset($request->options) && is_array($request->options)) ? json_encode($request->options) : "[]";
        }else{ 
            $dsi_meta->options = "[]";
        }
        $result = $dsi_meta->save();
        if($result){
            return redirect()->route('dsi.meta.index',['dsi_id' => $dsi_id])->with('mensaje', 'Registro ingresado con éxito!');
        }else{
            return redirect()->route('dsi.meta.index',['dsi_id' => $dsi_id])->with('alerta', 'Error, el registro no ha sido ingresado');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dsi_id, $id)
    {
        dd("show $dsi_id + $id values");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($dsi_id, $id)
    {
        dd("edit $dsi_id + $id");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd("update $dsi_id + $id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($dsi_id, $id)
    {
        dd("update $dsi_id + $id");
    }
    public function restore($dsi_id, $id)
    {
        dd("update $dsi_id + $id");
    }
    public function history($dsi_id, $id)
    {
        dd("update $dsi_id + $id");
    }
}
