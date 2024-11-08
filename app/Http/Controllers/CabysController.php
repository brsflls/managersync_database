<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CabysController extends Controller
{
    public function importarCabys()
    {
        try {
            $url = 'https://www.bccr.fi.cr/indicadores-economicos/cabys/Cabys_catalogo_historial_de_cambios.xlsx';
            $filePath = storage_path('app/public/Cabys_catalogo_historial_de_cambios.xlsx');
            $fileContent = file_get_contents($url);

            if ($fileContent === false) {
                \Log::error('Error al descargar el archivo CABYS');
                return response()->json(['error' => 'Error al descargar el archivo CABYS'], 500);
            }

            \Log::info('Archivo CABYS descargado con éxito');
            file_put_contents($filePath, $fileContent);

            $reader = new Xlsx();
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            $cabysData = [];
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                if (count($rowData) >= 9) {
                    $cabysData[] = [
                        'codigo_cabys_categoria_1' => $rowData[0] ?? null,
                        'descripcion_categoria_1' => $rowData[1] ?? null,
                        'codigo_cabys_categoria_2' => $rowData[2] ?? null,
                        'descripcion_categoria_2' => $rowData[3] ?? null,
                        'codigo_cabys_categoria_3' => $rowData[4] ?? null,
                        'descripcion_categoria_3' => $rowData[5] ?? null,
                        'impuesto' => $rowData[6] ?? null,
                    ];
                }
            }

            \Log::info('Datos CABYS:', ['array' => $cabysData, 'cantidad_filas' => count($cabysData)]);
            $jsonCabys = json_encode($cabysData);
            Storage::disk('public')->put('cabys.json', $jsonCabys);

            return response()->json(['data' => $cabysData, 'cantidad_filas' => count($cabysData)], 200);

        } catch (\Exception $e) {
            \Log::error('Error procesando el archivo CABYS: ' . $e->getMessage());
            return response()->json(['error' => 'Error procesando el archivo CABYS: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerCabysJson()
    {
        try {
            $filePath = storage_path('app/public/cabys.json');
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'El archivo CABYS no existe.'], 404);
            }

            $jsonContent = file_get_contents($filePath);
            $data = json_decode($jsonContent, true);

            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {
            \Log::error('Error al obtener el archivo CABYS JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener el archivo CABYS JSON.'], 500);
        }
    }
}
