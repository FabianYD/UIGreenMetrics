<?php

namespace App\Services;

use App\Models\Energy;
use App\Models\Water;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ReportService
{
    private function sanitizeText($text)
    {
        if ($text === null) {
            return '';
        }
        return trim(preg_replace('/[^\x20-\x7E]/', '', $text));
    }

    private function generateChartImage($data, $title)
    {
        // Configurar datos del gráfico
        $chartData = [
            'type' => 'bar',
            'data' => [
                'labels' => array_keys($data),
                'datasets' => [[
                    'label' => $title,
                    'data' => array_values($data),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ]]
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ]
                ]
            ]
        ];

        // Usar QuickChart.io para generar el gráfico
        $response = Http::get('https://quickchart.io/chart', [
            'c' => json_encode($chartData),
            'w' => 600,
            'h' => 300,
            'format' => 'png'
        ]);

        if ($response->successful()) {
            $tempFile = tempnam(sys_get_temp_dir(), 'chart') . '.png';
            file_put_contents($tempFile, $response->body());
            return $tempFile;
        }

        return null;
    }

    private function generateDocument($title, $data, $chartData = null)
    {
        $phpWord = new PhpWord();
        
        // Agregar estilos
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
        
        $section = $phpWord->addSection();
        
        // Agregar logo y título
        if (file_exists(public_path('images/logo.jpeg'))) {
            $section->addImage(public_path('images/logo.jpeg'), [
                'width' => 100,
                'height' => 100,
                'alignment' => 'center'
            ]);
        }
        
        // Título y subtítulo
        $section->addTitle('Template for Evidence(s)', 1);
        $section->addTitle('UI GreenMetric Questionnaire', 2);
        
        // Información básica
        $table = $section->addTable(['borderSize' => 0]);
        $this->addTableRow($table, 'Universidad', $this->sanitizeText(config('app.university_name')));
        $this->addTableRow($table, 'Pais', $this->sanitizeText(config('app.country')));
        $this->addTableRow($table, 'Sitio Web', $this->sanitizeText(config('app.url')));
        
        $section->addTextBreak();
        
        // Detalles del consumo
        $section->addTitle($title, 2);
        $table = $section->addTable(['borderSize' => 0]);
        
        foreach ($data as $label => $value) {
            $this->addTableRow($table, $label, $this->sanitizeText($value));
        }

        // Agregar gráfico si existe
        if ($chartData) {
            $section->addTextBreak();
            $section->addTitle('Estadísticas', 2);
            
            $chartPath = $this->generateChartImage(
                $chartData['data'],
                $chartData['title']
            );
            
            if ($chartPath && file_exists($chartPath)) {
                $section->addImage($chartPath, [
                    'width' => 400,
                    'height' => 300,
                    'alignment' => 'center'
                ]);
                unlink($chartPath);
            }
        }
        
        // Guardar temporalmente y retornar
        $tempFile = tempnam(sys_get_temp_dir(), 'report');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        $content = file_get_contents($tempFile);
        unlink($tempFile);
        
        return $content;
    }

    public function generateEnergyReport(Energy $energy)
    {
        $data = [
            'Tipo de Consumo' => $energy->tipo_energia ?? 'No especificado',
            'Consumo Total' => ($energy->consumo ?? '0') . ' ' . ($energy->unidad_medida ?? ''),
            'Fecha de Registro' => $energy->fecha_registro ? $energy->fecha_registro->format('d/m/Y') : 'No especificada',
            'Costo' => '$' . number_format($energy->costo ?? 0, 2),
            'Ubicacion' => $energy->ubicacion ?? 'No especificada',
            'Descripcion' => $energy->descripcion ?? 'Sin descripcion'
        ];

        // Obtener datos históricos para el gráfico
        $historicalData = Energy::select(
            DB::raw('EXTRACT(MONTH FROM fecha_registro) as month'),
            DB::raw('SUM(consumo) as total_consumo')
        )
        ->where('tipo_energia', $energy->tipo_energia)
        ->whereYear('fecha_registro', now()->year)
        ->groupBy(DB::raw('EXTRACT(MONTH FROM fecha_registro)'))
        ->orderBy('month')
        ->get()
        ->mapWithKeys(function($item) {
            $month = date('M', mktime(0, 0, 0, (int)$item->month, 1));
            return [$month => $item->total_consumo];
        })
        ->toArray();

        $chartData = [
            'data' => $historicalData,
            'title' => 'Consumo de Energía por Mes'
        ];
        
        return $this->generateDocument('Detalles del Consumo de Energia', $data, $chartData);
    }

    public function generateWaterReport(Water $water)
    {
        $data = [
            'ID de Medidor' => $water->medidor_id ?? 'No especificado',
            'Tipo de Consumo' => $water->tipo_consumo ?? 'No especificado',
            'Consumo Total' => ($water->consumo_total ?? '0') . ' m3',
            'Fecha de Pago' => $water->fecha_pago ? $water->fecha_pago->format('d/m/Y') : 'No especificada',
            'Ubicacion' => $water->ubicacion ?? 'No especificada',
            'Descripcion' => $water->descripcion ?? 'Sin descripcion'
        ];

        // Obtener datos históricos para el gráfico
        $historicalData = Water::select(
            DB::raw('EXTRACT(MONTH FROM fecha_pago) as month'),
            DB::raw('SUM(consumo_total) as total_consumo')
        )
        ->where('tipo_consumo', $water->tipo_consumo)
        ->whereYear('fecha_pago', now()->year)
        ->groupBy(DB::raw('EXTRACT(MONTH FROM fecha_pago)'))
        ->orderBy('month')
        ->get()
        ->mapWithKeys(function($item) {
            $month = date('M', mktime(0, 0, 0, (int)$item->month, 1));
            return [$month => $item->total_consumo];
        })
        ->toArray();

        $chartData = [
            'data' => $historicalData,
            'title' => 'Consumo de Agua por Mes'
        ];
        
        return $this->generateDocument('Detalles del Consumo de Agua', $data, $chartData);
    }

    private function addTableRow($table, $label, $value)
    {
        $row = $table->addRow();
        $row->addCell(2000)->addText($label, ['bold' => true]);
        $row->addCell(6000)->addText($value);
    }
}
