<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\sigmel_probando;
class ProbandoController extends Controller
{
    public function index(){

        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        /* $nuevos = new sigmel_probando;
        $nuevos->nombre = 'PRUEBA NOMBRE PAULA C';
        $nuevos->save(); */

        // $id_ult = sigmel_probando::on('mysql2')->select('id')->latest('id')->first();
        // echo "<pre>";
        // print_r($id_ult['id']);
        // echo "</pre>";

        $datos_pruebas = sigmel_probando::on('mysql2')->get();
        $user= Auth::user();
        return view ('otra_conexion', compact('datos_pruebas', 'user'));
    }

    /* EJEMPLO 1 */
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

    /* EJEMPLO 2 */
    public function generar(){
        $datos_pruebas = sigmel_probando::on('mysql2')->get();
        // $datos_pruebas = sigmel_probando::on('mysql2')->where('id', '2')->get();

        $info = json_decode(json_encode($datos_pruebas), true);
        echo "<pre>";
        print_r($info);
        echo "</pre>";

        /* CREAR UN NUEVO DOCUMENTO */
        $documento = new Spreadsheet();

        /* SE TRABAJA SOBRE LA HOJA 0 */
        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("Productos");

        /* GENERAR ENCABEZADOS DE CADA COLUMNA */
        $encabezado = ["ID", "NOMBRE", "FECHA CREACIÓN", "FECHA ACTUALIZACIÓN"];
        $hoja->fromArray($encabezado, null, 'A1');

        /* INSERCIÓN DE DATOS */
        $am = array();
        for ($i=0; $i < count($info); $i++) { 
            array_push($am, $info[$i]['id']);
            $am = [array_keys($info[$i])];
        };
        echo "<pre>";
        print_r($am);
        echo "</pre>";

        
        /* $nombreDelDocumento = "PROBANDO_REG_DB.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output'); */

    }
}
?>