<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReporteGeneralSeeder extends Seeder
{
    public function run()
    {
        // IDs de usuarios existentes (asegúrate de que estos usuarios existan en tu base de datos)
        $usuarioIds = [1, 2, 3]; // Cambia estos IDs a los que tengas en tu base de datos

        foreach (range(1, 10) as $index) {
            DB::table('reportes_generales')->insert([
                'usuario_id' => $usuarioIds[array_rand($usuarioIds)], // Selección aleatoria de un usuario existente
                'tipo' => $this->getTipoReporte(),
                'fecha_inicio' => $this->getRandomDate(),
                'fecha_fin' => $this->getRandomDate(),
                'contenido' => $this->getContenido(),
                'formato' => $this->getFormato(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getTipoReporte()
    {
        $tipos = [
            'Reporte de Ventas Totales',
            'Reporte de Compras Totales',
            'Reporte de Inventario',
            'Reporte de Clientes',
            'Reporte de Proveedores',
        ];
        return $tipos[array_rand($tipos)];
    }

    private function getRandomDate()
    {
        return Carbon::now()->subDays(rand(1, 30))->format('Y-m-d');
    }

    private function getContenido()
    {
        $contenidos = [
            'Reporte generado automáticamente.',
            'Datos completos sobre ventas y compras.',
            'Resumen mensual de inventario.',
            'Informe detallado de clientes.',
            'Datos actualizados de proveedores.',
        ];
        return $contenidos[array_rand($contenidos)];
    }

    private function getFormato()
    {
        $formatos = ['PDF', 'Excel'];
        return $formatos[array_rand($formatos)];
    }
}
