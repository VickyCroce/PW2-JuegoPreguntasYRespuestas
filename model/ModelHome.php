<?php

namespace model;
class ModelHome
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}