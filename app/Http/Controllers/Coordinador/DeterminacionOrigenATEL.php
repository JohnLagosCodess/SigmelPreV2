<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\cndatos_eventos;

class DeterminacionOrigenATEL extends Controller
{
    public function mostrarVistaDtoATEL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $Id_evento_dto_atel=$request->Id_evento_calitec;
        $Id_asignacion_dto_atel = $request->Id_asignacion_calitec;

        $array_datos_DTO_ATEL = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_dto_atel));

        $numero_consecutivo = 0000000 + 1;
        // Formatear el número consecutivo a 7 dígitos
        $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT);

        //Traer Motivo de solicitud,
        $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
        ->select('Id_motivo_solicitud','Nombre_solicitud')
        ->where('ID_evento', $Id_evento_dto_atel)
        ->get();

        //Traer Información apoderado 
        $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nombre_apoderado','Nro_identificacion_apoderado')
        ->where('ID_evento', $Id_evento_dto_atel)
        ->get();

        // Traer Información laboral
        $array_datos_info_laboral=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as sla', 'sla.Id_arl', '=', 'sile.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'sile.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldms', 'sldms.Id_municipios', '=', 'sile.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select('sile.ID_evento', 'sile.Tipo_empleado','sile.Id_arl', 'sla.Nombre_arl', 'sile.Empresa', 'sile.Nit_o_cc', 'sile.Telefono_empresa',
        'sile.Email', 'sile.Direccion', 'sile.Id_departamento', 'sldm.Nombre_departamento', 'sile.Id_municipio', 
        'sldms.Nombre_municipio', 'sile.Id_actividad_economica', 'slae.Nombre_actividad', 'sile.Id_clase_riesgo', 
        'slcr.Nombre_riesgo', 'sile.Persona_contacto', 'sile.Telefono_persona_contacto', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 
        'sile.F_ingreso', 'sile.Cargo', 'sile.Funciones_cargo', 'sile.Antiguedad_empresa', 'sile.Antiguedad_cargo_empresa', 
        'sile.F_retiro', 'sile.Descripcion')
        ->where([['sile.ID_evento','=', $Id_evento_dto_atel]])
        ->orderBy('sile.F_registro', 'desc')
        ->limit(1)
        ->get();

        return view('coordinador.determinacionOrigenATEL', compact('user', 'array_datos_DTO_ATEL', 'numero_consecutivo', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_info_laboral'));
    }

    public function cargueListadoSelectoresDTOATEL(Request $request){
        $parametro = $request->parametro;

        if ($parametro == "tipo_de_evento_si") {
            $listado_tipos_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where('Estado', 'activo')
            ->whereNotIn('Nombre_evento', ['Sin Cobertura'])
            ->get();

            $info_tipos_evento = json_decode(json_encode($listado_tipos_evento, true));
            return response()->json($info_tipos_evento);
        }
        if ($parametro == "tipo_de_evento_no") {
            $listado_tipos_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where('Estado', 'activo')
            ->whereNotIn('Id_Evento', [1,2,3])
            ->get();

            $info_tipos_evento = json_decode(json_encode($listado_tipos_evento, true));
            return response()->json($info_tipos_evento);
        }
        if ($parametro == "motivo_solicitud") {
            $listado_motivos_solicitud = sigmel_lista_motivo_solicitudes::on('sigmel_gestiones')
            ->select('Id_Solicitud', 'Nombre_solicitud')
            ->where('Estado', 'activo')
            ->get();

            $info_motivos_solicitud = json_decode(json_encode($listado_motivos_solicitud, true));
            return response()->json($info_motivos_solicitud);
        }
    }
}
