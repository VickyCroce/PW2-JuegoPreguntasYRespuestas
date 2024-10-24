<?php

class Presenter
{
    public function __construct()
    {
    }

    public function show($view, $data = [])
    {
        extract($data);
        include_once($view);
    }
}
