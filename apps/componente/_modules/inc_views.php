<?php /* @var $this Ibe_Template */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta content="renan henrique abreu" name="renan abreu"/>
        <title>Application</title>
        <!-- Framework CSS -->        
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		
        <script type="text/javascript">
	        (function($){				

				$.ibeAction = {},
		        
	            $.ibeComponent = function(settings){
		            /**
					 * Configuracoes padrao
					 */
	                var config = {};
	                if (settings){$.extend(config, settings);}

					/**
					 * Funcoes padrao
					 */
	                var funcs = {
							notFound:function(){ alert( 'Funcionalidade inexistente'); },
							notAllow:function(){ alert('Acesso negado a funcionalidade');},
							beforeDefault:function(){},
							successDefault:function(){},
							completeDefault:function(){}
					}; 
					$.extend(funcs,$.ibeAction);



					/**
				     * Retorna um objeto com os nomes e valores dos campos encontrados 
				     * dentro do elemento (element)
				     */
				    getDatas = function(element){
				        var $el = $(element);
				        var $inputs = $el.find('input,select,textarea');
				        var data = {};
				        
				        $.each($inputs,function(index,element){
				            var name  = $(element).attr('name');
				            var value = $(element).val();
				            var type  = $(element).attr('type');
				            
				            if(type != 'checkbox'){
				                data[name] = value; 
				            }else{
				                if(typeof(data[name]) == 'undefined'){
				                    data[name] = new Array();
				                } 
				                data[name].push(value);
				            }
				        });
				                
				        return data;
				    };
				    
	                /**
	                 * Buscando componentes
	                 */
					var elements = $('[ibe-comp="true"]');
					var request = function(_url,_context,_complete,_before,_success,_error){
						_complete = _complete || function(){};
						_error = _error || function(){};
						_before = _before || function(){};
						_success = _success || function(){};
						
						$.ajax({
				            url:_url,
				            type:'POST',
				            data:getDatas(_context),
				            cache: false,
				            dataType:'json',
				            beforeSend:function(){
					            funcs.beforeDefault();
					            _before();
				            },
				            success:function(response){
					            funcs.successDefault(response);
					            _success(response);
				            },
				            complete:function(){
					            funcs.completeDefault();
					            _complete();
				            },
				            statusCode:{
				                404:function(){
					                funcs.notFound()
				                },	
				                403:function(){
					                funcs.notAllow()
					            }
				            }
				        });		
					};
				    
					// <buttton ibe-comp="true" ibe-complete="complete" ibe-before="complete" ibe-success="complete" ibe-event="click" ibe-action="" ibe-context="body" />
					$.each(elements,function(index,elm){
						 var $elm = $(elm);
						 var event   = $elm.attr("ibe-event");
						 var action   = $elm.attr("ibe-action");
						 var context   = $elm.attr("ibe-context");
						 var complete = funcs[$elm.attr("ibe-complete")];
						 var success = funcs[$elm.attr("ibe-success")];
						 var error = funcs[$elm.attr("ibe-error")];
						 var before = funcs[$elm.attr("ibe-before")];
						
						 $elm.bind(event,function(){
							request(action,context,complete,before,success,error);
							return false;
						 });
						
					});
					
	                return this;
	            };
	        })(jQuery);

	        $.ibeAction = {
				myComplete:function(){ console.log("completou");},
				mySuccess:function(resp){ alert(resp.response.msg); },
				myBefore:function(){ console.log("antes");}
	    	};
	    	
			$("document").ready(function(){
		    	$.ibeComponent();
			});

        </script>
    </head>
    <body>
            <?php $this->includeView('header')?>
        <div style="border:1px solid #ccc; padding: 5px;">
        	<input type="text" name="nome" value="Renan" />
            <?php echo $this->view_module; ?>
            
            <?php Ext_Component::init($this);?>
            <?php $this->cA->fix();?>
           
        </div>
    </body>
</html>
