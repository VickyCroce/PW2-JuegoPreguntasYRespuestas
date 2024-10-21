<?php

class MustachePresenter{
    private $mustache;
    private $partialsPathLoader;

    public function __construct($partialsPathLoader){
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
                'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsPathLoader )
            ));
        $this->partialsPathLoader = $partialsPathLoader;
    }

    //renderizar vistas
    public function render($contentFile){
        echo  $this->generateHtml($contentFile);
    }

    public function generateHtml($contentFile) {
       /* $contentAsString = file_get_contents(  $this->partialsPathLoader .'/header.mustache');*/
        $contentAsString = file_get_contents( $contentFile );
       /* $contentAsString .= file_get_contents($this->partialsPathLoader . '/footer.mustache');*/
        return $this->mustache->render($contentAsString);
    }

    public function generateHtmlForPDF($contentFile, $data=array()){
        $contentAsString = file_get_contents( $contentFile );
        return $this->mustache->render($contentAsString, $data);
    }
}
