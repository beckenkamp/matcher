<?php

class Cliente extends Base {
    
    public function __construct()
    {
        try {
            parent::__construct(public_path().'/bases/base_cliente.csv');
        } catch (ErrorException $e) {
            App::abort(500, $e);
        }
    }
    
}