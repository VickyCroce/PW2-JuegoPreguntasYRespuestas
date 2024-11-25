<?php

class GenerarGraficos {

    // Gráfico de barras
    public function generarGraficoBarras($data, $labels, $titulo = 'Gráfico de Barras') {
        require_once 'vendor/jpgraph/src/jpgraph.php';
        require_once 'vendor/jpgraph/src/jpgraph_bar.php';

        $graph = new Graph(800, 600);
        $graph->SetScale('textint');

        $graph->title->Set($titulo);
        $graph->xaxis->SetTickLabels($labels);
        $graph->xaxis->SetLabelAngle(45);
        $graph->yaxis->title->Set("Cantidad");

        $barplot = new BarPlot($data);
        $barplot->SetFillColor('orange');
        $barplot->value->Show();

        $graph->Add($barplot);

        $imagePath = 'public/img/Grafico/' . uniqid('grafico_barras_', true) . '.png';
        $graph->Stroke($imagePath);

        return $imagePath;
    }

    // Gráfico de torta
    public function generarGraficoTorta($data, $labels, $titulo = 'Gráfico de Torta') {
        require_once 'vendor/jpgraph/src/jpgraph.php';
        require_once 'vendor/jpgraph/src/jpgraph_pie.php';
        require_once 'vendor/jpgraph/src/jpgraph_pie3d.php';

        if (empty($data) || empty($labels)) {
            throw new Exception('Los datos o etiquetas para el gráfico de torta están vacíos.');
        }

        $graph = new PieGraph(800, 600);
        $graph->title->Set($titulo);

        // Crear la torta
        $p1 = new PiePlot3D($data);
        $p1->SetLegends($labels);
        $p1->SetTheme('pastel');
        $p1->ExplodeSlice(1);

        $graph->Add($p1);

        $imagePath = 'public/img/Grafico/' . uniqid('torta_', true) . '.png';
        $graph->Stroke($imagePath);

        return $imagePath;
    }


    // Método genérico para obtener gráficos
    public function obtenerGrafico($tipo, $data, $labels, $titulo) {
        switch ($tipo) {
            case 'barras':
                $imagePath = $this->generarGraficoBarras($data, $labels, $titulo);
                break;
            case 'torta':
                $imagePath = $this->generarGraficoTorta($data, $labels, $titulo);
                break;
            default:
                throw new Exception("Tipo de gráfico no válido.");
        }

        return json_encode(['imageUrl' => $imagePath]);
    }
}

