<?php

namespace helper;
class Presenter
{
    public function __construct()
    {
    }

    public function show($view, $data = [])
    {
        extract($data);  // Esto hará que las claves del array $data estén disponibles como variables en la vista.
        include_once($view);
    }
}
