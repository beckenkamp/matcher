<?php

class Concorrente extends Base {
    
    public function __construct()
    {
        try {
            parent::__construct(public_path().'/bases/base_concorrente.csv');
        } catch (ErrorException $e) {
            App::abort(500, $e);
        }
    }
    
    /**
     * Return the better match by the similarity of the title
     *
     * @param $title string
     *
     * @return array
     */ 
    public function getByTitleSimilarity($title)
    {
        $key = key(Base::searchByTextSimilarity($title, "titulo", $this->data));
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return false;
        }
    }
    
    
    /**
     * Return the better match by the similarity of the choosen field
     *
     * @param $title string
     * @param $field string
     * @param $array array
     *
     * @return array
     */ 
    public function getByFieldSimilarity($value, $field, $array)
    {   
        $filtered = $this->getSelectedMatches($array);
        $match = Base::searchByTextSimilarity($value, $field, $filtered);

        return $match;
    }
    
}