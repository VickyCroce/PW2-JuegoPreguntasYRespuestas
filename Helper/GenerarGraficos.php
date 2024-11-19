<?php

class GenerarGraficos {

    public function generarGrafico($data, $labels, $titulo = 'Gráfico') {
        require_once 'vendor/jpgraph/src/jpgraph.php';
        require_once 'vendor/jpgraph/src/jpgraph_bar.php';

        // Crear gráfico
        $graph = new Graph(800, 600);
        $graph->SetScale('textint');

        // Configuración del gráfico
        $graph->title->Set($titulo);
        $graph->xaxis->SetTickLabels($labels);
        $graph->xaxis->SetLabelAngle(45);
        $graph->yaxis->title->Set("Cantidad");

        // Crear la barra
        $barplot = new BarPlot($data);
        $barplot->SetFillColor('orange');
        $barplot->value->Show();

        $graph->Add($barplot);

        // Guardar la imagen
        $imagePath = 'public/img/Grafico/' . uniqid('grafico_', true) . '.png';
        $graph->Stroke($imagePath);

        return $imagePath;
    }

    public function obtenerGrafico($data, $labels, $titulo) {
        $imagePath = $this->generarGrafico($data, $labels, $titulo);
        return json_encode(['imageUrl' => $imagePath]);
    }
}