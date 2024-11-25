<?php

namespace Helper;
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
class PdfCreator
{

    private $dompdf;
    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);  // Esto es importante si el HTML usa PHP en su interior
        $this->dompdf = new Dompdf($options);

    }

    public function create($html)
    {
        try {
            $this->dompdf->loadHtml($html);

            $this->dompdf->setPaper('A4', 'portrait');

            $this->dompdf->render();

            return $this->dompdf->stream("documento.pdf", ['Attachment' => 0]);
        } catch (\Exception $e) {
            error_log("PDF generation error: " . $e->getMessage());
            return false;
        }
    }
    }
