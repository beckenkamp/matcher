<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Matcher system example">
    <meta name="author" content="">

    <title>Matcher</title>

    <!-- Bootstrap core CSS -->
    <link href="static/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="static/jumbotron-narrow.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <div class="header">
		<nav>
			<ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="#" data-toggle="modal" data-target="#about-modal">Sum&aacute;rio</a></li>
			</ul>
		</nav>
        <h3 class="text-muted">Matcher</h3>
      </div>

      <div class="jumbotron">
        <h1>Matcher</h1>
        <p class="lead">
			Este &eacute; um exemplo de utiliza&ccedil;&atilde;o do sistema que
			busca matchs de produtos do cliente em um banco de dados com os produtos concorrentes.
		</p>
        <p><a class="btn btn-lg btn-success" href="https://github.com/markusbeck/matcher" role="button">Ver no GitHub</a></p>
      </div>

      <div id="product-list" class="row marketing">
		
      </div>
	  
	  <div id="pagination">
		<a id="load-more" class="btn btn-default" href="javascript:void(0);" role="button" data-offset="16">Carregar Mais</a>
	  </div>

      <footer class="footer">
        <p>&copy; Marcus Beckenkamp 2014</p>
      </footer>

    </div> <!-- /container -->
	
	
	<div class="modal fade" id="match-modal" tabindex="-1" role="dialog" aria-labelledby="MatchModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="MatchModalLabel"></h4>
				</div>
				<div class="modal-body">
				  ...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			  </div>
		</div>
	</div>
	
	<div class="modal fade" id="about-modal" tabindex="-1" role="dialog" aria-labelledby="AboutModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="AboutModalLabel">Sum&aacute;rio</h4>
				</div>
				<div class="modal-body">
					<p>
						Este sistema simples foi feito como um teste de engenharia de software.
						Seu c&oacute;digo fonte est&aacute; dispon&iacute;vel no <a href="https://github.com/markusbeck/matcher">GitHub</a>.
					</p>
					
					<hr>
					<h4>Solution description</h4>
					<p>Using the <strong>Laravel PHP Framework</strong> as a base, I've created a simple <em>REST service</em> which receives the product and compares it by the title similarity, using the PHP functions <em>similar_text</em> and <em>levenshtein</em>.</p>
					<p>First the algorithm breaks the product title and compares each word with the competitor's product's title using <em>similar_text</em> function. This phase accepts only 100% word matches to calculate a percentage of similarity of the titles, I call it <em>similarity by word</em>. </p>
					<p>Then it compares the whole title similarity using the <em>similar_text</em> function. A product can passes this phase if it have until 65% of similarity.</p>
					<p>The last step is to get only the results which passes the tests of <em>similarity by word</em> and <em>similarity by whole title</em> and sort it using the data of <em>similarity by word</em>, <em>levenshtein</em> and <em>similar_text</em>, resulting in the better match to the product.</p>
					
					<hr>
					<p>
						Veja os dados de acerto do algorítmo:
					</p>
				  	<h4><small>Acertos:</small> 69,78%</h4>
					<h4><small>Erros:</small> 30.22%</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			  </div>
		</div>
	</div>


	<script src="static/jquery-2.1.1.min.js"></script>
    <script src="static/bootstrap.min.js"></script>
	<script>
		var global_offset = 0;
		
		$(document).ready(function(){
		
			getProducts(0);
			
			$("#load-more").click(function(){
				getProducts(global_offset);
			});

		});
		
		function getProducts(offset)
		{
			showLoading();
			$.getJSON('index.php/list', {'offset' : offset}, function(data){
				html = '';
				last_key = null;
				
				if (offset == 0)
					global_offset = 0;
				
				$.each(data, function(key, value) {	
					
					html += '<div class="pull-right text-right">';
					html += '<h4>R$ '+value.preco.replace('.', ',')+'</h4>';
					html += '<a class="make-match btn btn-xs btn-default" href="javascript:void(0);" role="button" data-row="'+key+'">Ver Match de Produto</a>';
					html += '</div>';
					html += '<h4>'+value.titulo+'</h4>';
					html += '<ul>';
					html += '<li>Categoria: '+value.categoria+'</li>';
					html += '<li>Departamento: '+value.departamento+'</li>';
					html += '<li>Fabricante: '+value.meta_fabricante+'</li>';
					html += '<li>SKU: '+value.sku+'</li>';
					html += '</ul>';
					
					
					last_key = key;
				});
				
				$('#product-list').append(html);
				$('#loading').remove();
				global_offset = (last_key*1) + 1;
				
				$(".make-match").on('click', function(){
					row = $(this).data('row');
					getMatch(row);
				});
				
			});
		}
		
		function showLoading()
		{
			$('#product-list').append('<p id="loading" class="text-center">Carregando...</p>');
		}
		
		function getMatch(row)
		{
			$('.modal-body').html('');
			$('#MatchModalLabel').html('Carregando...');
			
			html = '<div class="row">';
			
			$.getJSON('index.php/product', {'row' : row}, function(data){
				
				html += '<div class="col-lg-6">';
				html += '<h4><small>Cliente:</small><br>'+data.titulo+'</h4>';
				html += '<ul>';
				html += '<li>Categoria: '+data.categoria+'</li>';
				html += '<li>Departamento: '+data.departamento+'</li>';
				html += '<li>Fabricante: '+data.meta_fabricante+'</li>';
				html += '<li>SKU: '+data.sku+'</li>';
				html += '<li>Pre&ccedil;o: '+data.preco+'</li>';
				html += '<li>Cor: '+data.cor+'</li>';
				html += '<li>G&ecirc;nero: '+data.meta_genero+'</li>';
				html += '</ul>';
				html += '</div>';
				
				$('#MatchModalLabel').html('Melhor match encontrado para este produto');
			});
			
			$.getJSON('index.php/match', {'row' : row}, function(data){
				
				html += '<div class="col-lg-6">';
				html += '<h4><small>Concorrente:</small><br>'+data.titulo+'</h4>';
				html += '<ul>';
				html += '<li>Categoria: '+data.categoria+'</li>';
				html += '<li>Departamento: '+data.departamento+'</li>';
				html += '<li>Fabricante: '+data.meta_fabricante+'</li>';
				html += '<li>SKU: '+data.sku+'</li>';
				html += '<li>Pre&ccedil;o: '+data.preco+'</li>';
				html += '<li>Cor: '+data.cor+'</li>';
				html += '<li>G&ecirc;nero: '+data.meta_genero+'</li>';
				html += '</ul>';
				html += '</div>';
				html += '</div>';
				
				$('.modal-body').html(html);
			});
			
			$('#match-modal').modal('toggle');
		}
	</script>
	
	
  </body>
</html>
