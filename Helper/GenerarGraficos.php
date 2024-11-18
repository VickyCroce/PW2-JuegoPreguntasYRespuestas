<?php

class GenerarGraficos {

    public function generarGrafico() {

        $data = array(20, 34, 50, 80, 45);


        require_once 'vendor/jpgraph/src/jpgraph.php';
        require_once 'vendor/jpgraph/src/jpgraph_bar.php';
        require_once 'vendor/jpgraph/src/jpgraph_pie.php';


        $graph = new Graph(400, 300);
        $graph->SetScale('textint');


        $barplot = new BarPlot($data);
        $graph->Add($barplot);


        $graph->title->Set('Gráfico de Barras Ejemplo');
        $barplot->SetFillColor('orange');


        $imagePath = 'public/img/temp_graph.png';


        $graph->Stroke($imagePath);


        return $imagePath;
    }

    public function obtenerGrafico() {
        $imagePath = $this->generarGrafico();

        echo json_encode(['imageUrl' => $imagePath]);
    }
}


