<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\sigmel_probando;

use App\Exports\ProbandoExport;

use App\Imports\ProbandoImportCsvConEncabezados;
use App\Imports\ProbandoImportCsvSinEncabezados;

use App\Imports\ProbandoImportXslxConEncabezados;
use App\Imports\ProbandoImportXslxSinEncabezados;
use App\Models\sigmel_lista_solicitantes;
use Maatwebsite\Excel\Facades\Excel;

use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProbandoController extends Controller
{
    public function index(){

        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        // $datos_nuevos = [
        //     'nombre' => 'Michael Jackson',
        //     'F_prueba' => $date,
        //     'created_at' => $date,
        //     'updated_at' => $date
        // ];
        // sigmel_probando::on('mysql2')->insert($datos_nuevos);

        /* COMO OBTENER EL ID DE UN REGISTRO INSERTADO */
        /* $id_ult = sigmel_probando::on('mysql2')->select('id')->latest('id')->first();
        echo "<pre>";
        print_r($id_ult['id']);
        echo "</pre>"; */

        $datos_pruebas = sigmel_probando::on('mysql2')->get();
        $user= Auth::user();

        // Texto o datos que quieres codificar en el QR
        $datos = "Hola, este es un código QR generado en Laravel.";

        // Generar el código QR
        $codigoQR = QrCode::size(100)->generate($datos);

        return view ('otra_conexion', compact('datos_pruebas', 'user', 'codigoQR'));


    }

    /* EJEMPLO 1 PHP SPREADSHEET */
    public function generar1(){ 

        $mySpreadsheet = new Spreadsheet();

        // delete the default active sheet
        $mySpreadsheet->removeSheetByIndex(0);

        // Create "Sheet 1" tab as the first worksheet.
        $worksheet1 = new Worksheet($mySpreadsheet, "Sheet 1");
        $mySpreadsheet->addSheet($worksheet1, 0);

        // Create "Sheet 2" tab as the second worksheet.
        $worksheet2 = new Worksheet($mySpreadsheet, "Sheet 2");
        $mySpreadsheet->addSheet($worksheet2, 1);

        // sheet 1 contains the birthdays of famous people.
        $sheet1Data = [
            ["First Name", "Last Name", "Date of Birth"],
            ['Britney',  "Spears", "02-12-1981"],
            ['Michael',  "Jackson", "29-08-1958"],
            ['Christina',  "Aguilera", "18-12-1980"],
        ];

        // Sheet 2 contains list of ferrari cars and when they were manufactured.
        $sheet2Data = [
            ["Model", "Production Year Start", "Production Year End"],
            ["308 GTB",  1975, 1985],
            ["360 Spider",  1999, 2004],
            ["488 GTB",  2015, 2020],
        ];


        $worksheet1->fromArray($sheet1Data);
        $worksheet2->fromArray($sheet2Data);

        // Change the widths of the columns to be appropriately large for the content in them.
        // https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
        $worksheets = [$worksheet1, $worksheet2];

        foreach ($worksheets as $worksheet)
        {
            foreach ($worksheet->getColumnIterator() as $column)
            {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pruebas.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($mySpreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /* EJEMPLO 2 PHP SPREADSHEET */
    public function generar(){
        
        $datos_pruebas = sigmel_probando::on('mysql2')->get();
        // $datos_pruebas = sigmel_probando::on('mysql2')->where('id', '2')->get();

        $info = json_decode(json_encode($datos_pruebas), true);
    

        /* CREAR UN NUEVO DOCUMENTO */
        $documento = new Spreadsheet();

        /* SE TRABAJA SOBRE LA HOJA 0 */
        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("PROBANDO");

        /* GENERAR ENCABEZADOS DE CADA COLUMNA */
        $am = [["ID", "NOMBRE", "FECHA CREACIÓN", "FECHA ACTUALIZACIÓN"]];
        
        for ($a=0; $a < count($info); $a++) { 
            array_push($am, array($info[$a]['id'], $info[$a]['nombre'], $info[$a]['created_at'], $info[$a]['updated_at']));
        }

        $hoja->fromArray($am);

        foreach (range('A', $hoja->getHighestColumn()) as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
         }
        
        $nombreDelDocumento = "PROBANDO_REG_DB.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');

    }

    public function generarPDF1(){

        // Generar el código QR
        $datos = "Hola, este es un código QR generado en Laravel.";
        $codigoQR = QrCode::size(100)->generate($datos);

        $data = [
            'codigoQR' => $codigoQR,
            'nombre' => '$nombre_trabajador',
            'cedula' => '$cedula',
            'cargo' => '$cargo',
            'depar' => '$departamento_seccion',
            'edad' => '$edad',
            'sexo' => '$sexo',
            'aplicacion_cuestionario' => '$aplicacion_cuestionario',
            'nombre_empresa' => '$nombre_empresa',
            'nombre_evaluador' => '$nombre_evaluador',
            'cc_evaluador' => '$cc_evaluador',
            'profesion_evaluador' => '$profesion_evaluador',
            'posgrado_evaluador' => '$posgrado_evaluador',
            'tarjeta_profesional' => '$tarjeta_profesional_evaluador',
            'lice_ocupa_evaluador' => '$lice_ocupa_evaluador',
            'fe_lice_ocupa_evaluador' => '$fe_lice_ocupa_evaluador',
            'dim_1_int' => '$pun_transfor_dim_1_intra',
            'dim_2_int' => '$pun_transfor_dim_2_intra',
            'dim_3_int' => '$pun_transfor_dim_3_intra',
            'dim_4_int' => '$pun_transfor_dim_4_intra',
            'riesgo_dim_1_int' => '$nivel_riesgo_dim_1_intra',
            'riesgo_dim_2_int' => '$nivel_riesgo_dim_2_intra',
            'riesgo_dim_3_int' => '$nivel_riesgo_dim_3_intra',
            'riesgo_dim_4_int' => '$nivel_riesgo_dim_4_intra',
            'dom_1_int' => '$pun_transfor_dom_1_intra',
            'riesgo_dom_1_int' => '$nivel_riesgo_dom_1_intra',
            'dim_5_int' => '$pun_transfor_dim_5_intra',
            'dim_6_int' => '$pun_transfor_dim_6_intra',
            'dim_7_int' => '$pun_transfor_dim_7_intra',
            'dim_8_int' => '$pun_transfor_dim_8_intra',
            'dim_9_int' => '$pun_transfor_dim_9_intra',
            'riesgo_dim_5_int' => '$nivel_riesgo_dim_5_intra',
            'riesgo_dim_6_int' => '$nivel_riesgo_dim_6_intra',
            'riesgo_dim_7_int' => '$nivel_riesgo_dim_7_intra',
            'riesgo_dim_8_int' => '$nivel_riesgo_dim_8_intra',
            'riesgo_dim_9_int' => '$nivel_riesgo_dim_9_intra',
            'dom_2_int' => '$pun_transfor_dom_2_intra',
            'riesgo_dom_2_int' => '$nivel_riesgo_dom_2_intra',
            'dim_10_int' => '$pun_transfor_dim_10_intra',
            'dim_11_int' => '$pun_transfor_dim_11_intra',
            'dim_12_int' => '$pun_transfor_dim_12_intra',
            'dim_13_int' => '$pun_transfor_dim_13_intra',
            'dim_14_int' => '$pun_transfor_dim_14_intra',
            'dim_15_int' => '$pun_transfor_dim_15_intra',
            'dim_16_int' => '$pun_transfor_dim_16_intra',
            'dim_17_int' => '$pun_transfor_dim_17_intra',
            'riesgo_dim_10_int' => '$nivel_riesgo_dim_10_intra',
            'riesgo_dim_11_int' => '$nivel_riesgo_dim_11_intra',
            'riesgo_dim_12_int' => '$nivel_riesgo_dim_12_intra',
            'riesgo_dim_13_int' => '$nivel_riesgo_dim_13_intra',
            'riesgo_dim_14_int' => '$nivel_riesgo_dim_14_intra',
            'riesgo_dim_15_int' => '$nivel_riesgo_dim_15_intra',
            'riesgo_dim_16_int' => '$nivel_riesgo_dim_16_intra',
            'riesgo_dim_17_int' => '$nivel_riesgo_dim_17_intra',
            'dom_3_int' => '$pun_transfor_dom_3_intra',
            'riesgo_dom_3_int' => '$nivel_riesgo_dom_3_intra',
            'dim_18_int' => '$pun_transfor_dim_18_intra',
            'dim_19_int' => '$pun_transfor_dim_19_intra',
            'riesgo_dim_18_int' => '$nivel_riesgo_dim_18_intra',
            'riesgo_dim_19_int' => '$nivel_riesgo_dim_19_intra',
            'dom_4_int' => '$pun_transfor_dom_4_intra',
            'riesgo_dom_4_int' => '$nivel_riesgo_dom_4_intra',
            'total_int' => '$pun_transfor_total_intra',
            'riesgo_total_int' => '$nivel_riesgo_total_intra',
            'observ_coment_evaluador_intra' => '$observ_coment_evaluador_intra',
            'recomend_parti_evaluador_intra' => '$recomend_parti_evaluador_intra',
            'dim_1_ext' => '$pun_transfor_dim_1_extra',
            'dim_2_ext' => '$pun_transfor_dim_2_extra',
            'dim_3_ext' => '$pun_transfor_dim_3_extra',
            'dim_4_ext' => '$pun_transfor_dim_4_extra',
            'dim_5_ext' => '$pun_transfor_dim_5_extra',
            'dim_6_ext' => '$pun_transfor_dim_6_extra',
            'dim_7_ext' => '$pun_transfor_dim_7_extra',
            'riesgo_dim_1_ext' => '$nivel_riesgo_dim_1_extra',
            'riesgo_dim_2_ext' => '$nivel_riesgo_dim_2_extra',
            'riesgo_dim_3_ext' => '$nivel_riesgo_dim_3_extra',
            'riesgo_dim_4_ext' => '$nivel_riesgo_dim_4_extra',
            'riesgo_dim_5_ext' => '$nivel_riesgo_dim_5_extra',
            'riesgo_dim_6_ext' => '$nivel_riesgo_dim_6_extra',
            'riesgo_dim_7_ext' => '$nivel_riesgo_dim_7_extra',
            'total_ext' => '$pun_transfor_total_extra',
            'riesgo_total_ext' => '$nivel_riesgo_total_extra',
            'observ_coment_evaluador_extra' => '$observ_coment_evaluador_extra',
            'recomend_parti_evaluador_extra' => '$recomend_parti_evaluador_extra',
            'total_estres' => '$pun_transfor_total_estres',
            'riesgo_total_estres' => '$nivel_riesgo_total_estres',
            'observ_coment_evaluador_estres' => '$observ_coment_evaluador_estres',
            'recomend_parti_evaluador_estres' => '$recomend_parti_evaluador_estres',
            'fecha_elab_info' => '$fecha_elab_info'
        ];

        // $pdf = app('dompdf.wrapper');
        // $pdf->loadView('/planti', $data);
        // $fileName = 'pdfsito.pdf';
        // $pdf->set('isHtml5ParserEnabled', true);
        // $pdf->set('isPhpEnabled', true);
        // return $pdf->download($fileName);

       // Cargar la plantilla principal
        $html = view('/planti', $data)->render();

        // Configurar DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Crear instancia de DomPDF
        $dompdf = new Dompdf($options);

        // Cargar HTML en DomPDF
        $dompdf->loadHtml($html);

        // Establecer tamaño de papel y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar PDF
        $dompdf->render();

        // Descargar o mostrar el PDF generado
        return $dompdf->stream('pdf_prueba.pdf');

    }

    // mostrarProformas
    public function generarPDF(){
        if(!Auth::check()){
            return redirect('/');
        }
        
        $user= Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);

        $nombreCiudad = "Bogotá";

        // Crear un objeto Carbon con la fecha y hora actuales
        $fechaActual = Carbon::now();
        
        // Configurar la zona horaria (puedes ajustarla según tu ubicación)
        $fechaActual->setTimezone('America/Bogota');
        
        // Establecer la localización a español
        $fechaActual->locale('es');
        
        // Formatear la fecha actual según tus especificaciones
        $fechaFormateada = $fechaActual->format('F d \d\e Y');


        $diagnosticos_cie10 = array("Manzana", "Plátano", "Uva", "Pera", 
        "Banano", "Sandía", "Papaya", "Durazno", "Arándanos");
        
        $data = [
            'ciudad' => $nombreCiudad,
            'fecha' => $fechaFormateada,
            'nombre_afiliado' => 'Mauro Estefan Ramírez Aranguren',
            'correo_afiliado' => 'mauro.ramirez@codess.org.co',
            'direccion_afiliado' => 'Calle 41 A Sur # 72 H - 03',
            'telefonos_afiliado' => '3124431689',
            'municipio_afiliado' => 'Bogotá D.C.',
            'departamento_afiliado' => 'Bogotá D.C.',
            'nro_radicado' => '1234578',
            'tipo_identificacion' => 'Cc',
            'num_identificacion' => '1030651087',
            'nro_siniestro' => '987456321',
            'identificacion' => '1030651087',
            'fecha_evento' => $date,
            'diagnosticos_cie10' => $diagnosticos_cie10,
            'Firma_cliente' => 'gola',
            'nombre_usuario' => Auth::user()->name
        ];

        $html = view('/Proformas/Proformas_Arl/Origen_Atel/notificacion_dml_origen', $data)->render();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream('pdf_prueba.pdf');



        // $nombre_afiliado = 'Mauro Estefan Ramírez Aranguren';
        // $correo_afiliado = 'mauro.ramirez@codess.org.co';
        // $direccion_afiliado = 'Calle 41 A Sur # 72 H - 03';
        // $telefonos_afiliado = '3124431689';
        // $municipio_afiliado = 'Bogotá D.C.';
        // $departamento_afiliado = 'Bogotá D.C.';
        // $identificacion = '1030651087';
        // $fecha_evento = $date;
        
        // return view ('Proformas.Proformas_Arl/Origen_Atel.notificacion_dml_origen', compact('user', 
        // 'nombreCiudad',
        // 'fechaFormateada',
        // 'nombre_afiliado',
        // 'correo_afiliado',
        // 'direccion_afiliado',
        // 'telefonos_afiliado',
        // 'municipio_afiliado',
        // 'departamento_afiliado',
        // 'identificacion',
        // 'fecha_evento'
        // ));
    }


    /* EJEMPLO 1 LARAVEL EXCEL (LIBRERIA) */
    /* 
        ESTE EJEMPLO PRETENDE SUBIR UN ARCHIVO CSV A LA TABLA SIGMEL_PROBANDOS
        Y FINALMENTE PODER EXPORTARLO.
        PARA ELLO EL PRIMER PASO ES CREAR EL PROCESO DE IMPORTACION:
        COMANDO: php artisan make:import ProbandoImport --model=sigmel_probando
        ESTO CREARÁ UNA CARPETA NUEVA LLAMADA IMPORTS ALLÍ ESTARÁ EL ARCHIVO

        EL SEGUNDO PASO ES CREAR EL PROCESO DE EXPORTACIÓN
        COMANDO: php artisan make:export ProbandoExport --model=sigmel_probando
        ESTO CREARÁ UNA CARPETA NUEVA LLAMADA EXPORTS ALLÍ ESTARÁ EL ARCHIVO
    */
    public function ExportarArchivo() 
    {
        return Excel::download(new ProbandoExport, 'probando.xlsx');
    }

    /* CSV */
    public function importarCsvConEncabezados() 
    {
        Excel::import(new ProbandoImportCsvConEncabezados,request()->file('file_csv_con_encabezados'));
        return back();
    }

    public function importarCsvSinEncabezados(){
        Excel::import(new ProbandoImportCsvSinEncabezados,request()->file('file_csv_sin_encabezados'));
        return back();
    }


    /* XLSX */
    public function ImportarXlsxConEncabezados(){
        Excel::import(new ProbandoImportXslxConEncabezados,request()->file('file_xlsx_con_encabezados'));
        return back();
    }

    public function importarXlsxSinEncabezados(){
        Excel::import(new ProbandoImportXslxSinEncabezados,request()->file('file_xlsx_sin_encabezados'));
        return back();
    }

    
    
}
?>