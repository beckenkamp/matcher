<?php

class Base {
    
    protected $file;
    protected $data;
    protected $fields;
    
    /**
     * Constructor that reads the database files and load the data to the class
     *
     * @param file CSV
     */ 
    public function __construct($file)
    {
        if (file_exists($file)) {
            $this->file = $file;
            $handle = fopen ($file,"r");
            $row = 0;
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                
                $num = count ($data);
                for ($c=0; $c < $num; $c++) {
                    if ($row == 0) {
                        $this->fields[$c] = $data[$c];
                    } else {
                        $this->data[$row][$this->fields[$c]] = $data[$c];   
                    }
                }
                
                $row++;
            }
            
            fclose ($handle);
        } else {
            throw ErrorException(sprintf('Arquivo base %s inexistente!', $file));
        }
    }
    
    /**
     * Get all the database data, could be paginated or not
     *
     * @param $offset integer
     * @param $limit integer
     *
     * @return array
     */ 
    public function getData($offset = null, $limit = 16)
    {
        if ($offset !== null) {
            $paginated = array();
            $final_row = $offset + $limit;
            
            foreach ($this->data as $row => $data) {
                if ($row >= $offset && $row <= $final_row) {
                    $paginated[$row] = $data;
                }
            }
            
            return $paginated;
        }
        
        return $this->data;
    }
    
    /**
     * Get an specific product by the row that it is on database file
     *
     * @param $id integer
     */ 
    public function getById($id)
    {
        return $this->data[$id];
    }
    
    /**
     * Get the title of a product by the row that it is on database file
     *
     * @param $row integer
     *
     * @return array || false
     */
    public function getTitle($row)
    {
        if (isset($this->data[$row])) {
            return Base::slugify($this->data[$row]["titulo"]);
        } else {
            return false;
        }
    }
    
    
    /**
     * Get the match number of a product
     *
     * @param $row integer
     * 
     * @return boolean
     */
    public function getMatch($row)
    {
        if (isset($this->data[$row])) {
            return $this->data[$row]["match"];
        } else {
            return false;
        }
    }
    
    
    /**
     * Search in the base by a specific field with text similitarity
     *
     * @param $text string
     * @param $field string Which field should look for
     * @param $array array
     *
     * @return array
     */ 
    public static function searchByTextSimilarity($text, $field, $array)
    {   
        $res = array();
        $slug = Base::slugify($text);
        $text = explode('-', $slug);
        
        foreach ($array as $row => $data) {
            if (isset($data[$field])) {
                $slug_to_compare = Base::slugify($data[$field]);
                $text_to_compare = explode('-', $slug_to_compare);
                
                $c=0;
                for ($i = 0; $i < count($text); $i++) {
                    if (isset($text_to_compare[$i])) {
                        foreach ($text_to_compare as $t) {
                        similar_text($t, $text[$i], $percent);
                        
                        if ($percent >= 100)
                            $c++; //$res[$row] = array('lev'=> $levenshtein, 'per' => $percent);
                        }
                    }
                }
                
                $similarity_by_word = $c/count($text)*100;
                
                $levenshtein = levenshtein($slug_to_compare, $slug);
                similar_text($slug_to_compare, $slug, $percent);
                
                if ($percent >= 65 || $similarity_by_word > 30)
                    $res[$row] = array('sim_by_word' => $similarity_by_word, 'per' => $percent,);
            }
        }
        
        arsort($res);
        
        return $res;
    }
    
    
    /**
     * Get the data of the matches that are returned by the function searchByTextSimilarity()
     *
     * @param $selected array
     *
     * #return array
     */ 
    public function getSelectedMatches($selected)
    {
        $array = array();
        
        foreach ($selected as $k => $v)
        {
            $array[$k] = $this->data[$k];
        }
        
        return $array;
    }
    
    
    /**
     * Verify if it is a match by the row
     *
     * @param $match integer
     * @param $row integer
     *
     * @return boolean
     */ 
    public function validateMatch($match, $row)
    {
        return ($match == $this->data[$row]["match"]);
    }
    
    
    /**
     * Transforms a string into a slug
     *
     * @param $text string
     *
     * @return string
     *
     * @link http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     */
    public static function slugify($text)
    { 
      // replace non letter or digits by -
      $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    
      // trim
      $text = trim($text, '-');
    
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
      // lowercase
      $text = strtolower($text);
    
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
    
      if (empty($text))
      {
        return 'n-a';
      }
    
      return $text;
    }
    
}