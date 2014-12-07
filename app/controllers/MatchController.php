<?php

class MatchController extends \BaseController {

    /**
     * Returns the client base as JSON
     *
     * @return array JSON
     */ 
	public function index()
    {
        $offset = Input::get('offset');
        $base_cliente = new Cliente;
        
        return json_encode($base_cliente->getData($offset));
    }
    
    
    /**
     * Returns a match to a product
     *
     * @param Request
     * @return array JSON
     */
	public function match()
	{
        $row = Input::get('row');
        
        $base_concorrente = new Concorrente;
        $base_cliente = new Cliente;
        $data = $base_cliente->getById($row);
        
        $match = $base_concorrente->getByTitleSimilarity($data["titulo"]);
        
        return json_encode($match);
	}
    
    
    /**
     * Returns a product
     *
     * @param Request
     * @return array JSON
     */
	public function getProduct()
	{
        $row = Input::get('row');
        
        $base_concorrente = new Concorrente;
        $base_cliente = new Cliente;
        $data = $base_cliente->getById($row);
        
        return json_encode($data);
	}
    
    
    /**
     * Calculates the percentage of success for the "matcher"
     * and returns a file with all the results
     *
     * @return string
     
    public function summary()
    {
        set_time_limit(1000000);
        
        $base_cliente = new Cliente;
        $base_concorrente = new Concorrente;
        
        $data_cliente = $base_cliente->getData();
        
        $c = 0;
        $valid = 0;
        $not_valid = 0;
        $total = count($data_cliente);
        $lista = array ();
        
        //first line of csv
        $lista[] = array (
            'exato',
            'match',
            'sku',
            'titulo',
            'departamento',
            'categoria',
            'preco',
            'preco_oferta',
            'meta_fabricante',
            'cor',
            'meta_tamanho',
            'mpn',
            'gtin',
            'meta_peso',
            'meta_volume',
            'meta_genero',
            'match_match',
            'match_sku',
            'match_titulo',
            'match_departamento',
            'match_categoria',
            'match_preco',
            'match_preco_oferta',
            'match_meta_fabricante',
            'match_cor',
            'match_meta_tamanho',
            'match_mpn',
            'match_gtin',
            'match_meta_peso',
            'match_meta_volume',
            'match_meta_genero'
        );
        
        foreach ($data_cliente as $data) {
            $match = $base_concorrente->getByTitleSimilarity($data["titulo"]);
            
            if ($match && $match['match'] == $data['match']) {
                $valid++;
                $valido = 'S';
            } else {
                $not_valid++;
                $valido = 'N';
            }
            
            $lista[] = array (
                $valido,
                $data['match'],
                $data['sku'],
                $data['titulo'],
                $data['departamento'],
                $data['categoria'],
                $data['preco'],
                $data['preco_oferta'],
                $data['meta_fabricante'],
                $data['cor'],
                $data['meta_tamanho'],
                $data['mpn'],
                $data['gtin'],
                $data['meta_peso'],
                $data['meta_volume'],
                $data['meta_genero'],
                $match['match'],
                $match['sku'],
                $match['titulo'],
                $match['departamento'],
                $match['categoria'],
                $match['preco'],
                $match['preco_oferta'],
                $match['meta_fabricante'],
                $match['cor'],
                $match['meta_tamanho'],
                $match['mpn'],
                $match['gtin'],
                $match['meta_peso'],
                $match['meta_volume'],
                $match['meta_genero'],
            );
            
            echo '.'; flush();
            $c++;
        }
        
        //writes the result file
        $fp = fopen(public_path().'/bases/match.csv', 'w');
            
        foreach ($lista as $linha) {
            fputcsv($fp, $linha);
        }
        
        fclose($fp);
        
        $acerto = $valid/$total*100;
        $erros = $not_valid/$total*100;
        
        echo '<br>Acertos: '.$acerto.'%';
        echo '<br>Erros: '.$erros.'%';
    }*/ 
    
}