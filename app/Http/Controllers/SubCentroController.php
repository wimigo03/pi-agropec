<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\SubCentro;
use App\Models\ComprobanteDetalle;
use App\Models\ComprobanteFDetalle;
use Auth;
use DB;

class SubCentroController extends Controller
{
    const ICONO = 'fas fa-donate fa-fw';
    const INDEX = 'SUBCENTROS CONTABLES';
    const CREATE = 'REGISTRAR SUBCENTRO CONTABLE';
    const EDITAR = 'MODIFICAR SUBCENTRO CONTABLE';

    public function index()
    {

    }

    public function create()
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        return view('subcentros.create', compact('icono','header','empresas'));
    }

    public function getCentros(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $centros = DB::table('centros')
                            ->where('empresa_id',$id)
                            ->where('estado','1')
                            ->select('nombre','id')
                            ->get()
                            ->toJson();
            if($centros){
                return response()->json([
                    'centros' => $centros
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:sub_centros,nombre,null,id,empresa_id,' . $request->empresa_id
        ]);
        try{
            $datos = [
                'centro_id' => $request->centro_id,
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => Auth::user()->pi_cliente_id,
                'nombre' => $request->nombre,
                'abreviatura' => $request->abreviatura,
                'create' => date('Y-m-d'),
                'tipo' => '1',
                'estado' => '1'
            ];

            $centro = SubCentro::create($datos);

            return redirect()->route('centros.index')->with('success_message', 'SubCentro registrado exitosamente.');
        } catch (ValidationException $e) {
            return redirect()->route('centros.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id)
    {
        try{
            $centro = SubCentro::find($id);
            $centro->update([
                'estado' => '1'
            ]);
            return redirect()->route('centros.index')->with('success_message', 'Centro Habilitado...');
        } catch (ValidationException $e) {
            return redirect()->route('centros.index')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id)
    {
        try{
            $comprobantes = ComprobanteDetalle::where('sub_centro_id',$id)->get()->count();
            if($comprobantes > 0){
                return redirect()->route('centros.index')->with('info_message', 'Accion no realizada. Usted tiene comprobantes comprometidos con este centro.');
            }
            $comprobantesf = ComprobanteFDetalle::where('sub_centro_id',$id)->get()->count();
            if($comprobantesf > 0){
                return redirect()->route('centros.index')->with('info_message', 'Accion no realizada. Usted tiene comprobantes comprometidos con este centro.');
            }
            $centro = SubCentro::find($id);
            $centro->update([
                'estado' => '2'
            ]);
            return redirect()->route('centros.index')->with('success_message', 'Centro deshabilitado...');
        } catch (ValidationException $e) {
            return redirect()->route('centros.index')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}
