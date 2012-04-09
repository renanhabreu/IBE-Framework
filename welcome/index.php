<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        
        <meta name="description" content="php framework" />
        <meta name="keywords" content="php,framework,php framework,simple php framework,sifraphp,ibe,ibe-framework" />
        <meta name="author" content="Renan Henrique Abreu" />
        
        <title>:: IBE ::</title>
        
        <!-- Framework CSS -->
        <link rel="stylesheet" href="css/screen.css" type="text/css" media="screen, projection"/>
        <link rel="stylesheet" href="css/print.css" type="text/css" media="print"/>
        <link rel="stylesheet" href="./css/main.css" type="text/css" />
        <!--[if lt IE 8]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection"/><![endif]-->
        <script type="text/javascript" src="js/jquery-1.7.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>
    <body>
        
        <!-- CONTEUDO -->
        <div class="container showgrids">
        
            <div  class="span-24 last">
                <h1>IBE FRAMEWORK 1.1 </h1>
                <h3>inspired + beautiful + elegant</h3>
            </div>
            
            <div class="clear span-24 last">
                <h4>O Framework</h4>
                <p>
                    <span class="tab"></span>Projetado para ser simples a sua utilização, manutenção e extensão, o IBE tem por objetivo ser principalmente um motor MVC para aplicativos PHP. Dentre várias características , conta com um pacote para criação de esqueletos para os aplicativos, chamado SKT, o qual pode ser estendido de maneira rápida e simples, desde que seguido algumas regras de implementação.
                </p>
                
                <div class="clear span-12">
                    <h4>Caracteríticas</h4>
                    <p>
                        <span class="tab"></span>
                        O IBE é um framework limpo e não possui algumas funcionalidades encontradas
                        em frameworks já consolidados. Isto porque o objetivo é promover a inspiração 
                        dos desenvolvedores através da criação dos pacotes de extensão, filtros e plugins.
                        Abaixo segue a lista de pacotes disponíveis:
                        
                    </p>
                    <ul>
                        <li>_extensions : 
                            <p> 
                                <span class="tab"></span>
                                É responsável por conter as extensões do aplicativo. 
                                Assim como discutido anteriormente, o framework tem a 
                                responsabilidade de trabalhar apenas com a estrutura MVC , 
                                desta forma todas as outras funcionalidades que elevam as 
                                características do IBE são conhecidas como extensões
                            </p>
                        </li>
                        <li>_filters : 
                            <p> 
                                <span class="tab"></span>
                                Os filtros são sempre disparados antes da execução de uma
                                action, realizam sua tarefa e em seguida são finalizados.
                                Um classe de filtro precisa apenas de estar localida no pacote
                                <em>_filtres</em>, extender a class <em>Ibe_Filter</em> e implementar o método
                                <em>execute</em>                                
                            </p>
                        </li>
                        
                        <li>_helpers : 
                            <p> 
                                <span class="tab"></span>
                                Ajudadores são as funcionalidades passíveis de serem utilizados
                                na camada de visão, estas porporcionam facilidades em tarefas repetitivas
                                e que não demandam grandes lógicas. Uma class helper precisa estar no pacote
                                <em>_helpers</em>, extender a class <em>Ibe_Helper</em> e implementar o método <em>execute</em>,
                            </p>
                        </li>
                        
                        <li>_maps : 
                            <p> 
                                <span class="tab"></span>
                                O pacote mapa é o único pacote que o framework da o suporte nativamente.
                                Ele é a parte ORM das aplicações, porém, o desenvolvedor não precisa se 
                                limitar a tal pacote. As classes aqui encontradas podem são criadas
                                automaticamente pelo Fabricante de códigos <em>map</em>
                            </p>
                        </li>
                        
                        <li>_modules : 
                            <p> 
                                <span class="tab"></span>
                                No pacote _modules estão localizados todos os módulos, controladores e views.
                                O IBE trabalha mantendo a separação de Aplicativos, Módulos, Controladores e Ações.
                                Dessa forma é possível trabalhar com o sistema de layout em um sentido mais amplo,
                                sob a hierarquia de Aplicativos.
                                Isso significa que diversos aplicativos podem possuir o mesmo layout padrão, não
                                se limitando assim a hierarquia de apenas um único aplicativo.
                            </p>
                        </li>
                        
                        <li>_plugins : 
                            <p> 
                                <span class="tab"></span>
                                Os plugins são um tipo diferente de extensão. Estes possuem 
                                suas regras BEM DEFINIDAS e ÚNICAS, estas são as pincipais diferenças
                                entre os pacotes _extensions e _plugins. Além disso os plugins devem 
                                extender a class Ibe_Plugin e implementar os métodos initialize,execute
                                e finalize e não podem ser instanciados com a palavra chave new.
                            </p>
                        </li>
                        
                        <li>_rsc : 
                            <p> 
                                <span class="tab"></span>
                                Este pacote é apenas uma padronização para o desenvolvimento  de aplicativos.
                                para manter o padrão de pacotes e organização dos aplicativos as subpastas
                                aqui dispostas são, por exemplo: js, img, css, upload
                            </p>
                        </li>
                    </ul>
                </div>
                
                <div class="span-12 last"> 
                    <h3 class="video-right">regido por 3 princípios de desenvolvimento</h3>
                    
                    <ul>
                        <li><b>I</b>nspiração do desenvolvedor(Extensibilidade)</li>
                        <li><b>B</b>eleza das aplicações(Simplicidade)</li>
                        <li><b>E</b>legância do desenvolvimento (MVC)</li>
                    </ul>
                    
                    <p>
                        <span class="tab"></span>O bom analista desenvolvedor sempre coloca em prática suas habilidades mais importantes, modelagem de dados e engenharia de requisitos.Todavia, esta tarefa é complexa, pois, além de modelar dados e levantar requisitos, concentra esforços em realizar projetos arquiteturais.
                        <br/><br/>

<span class="tab"></span>Graças aos frameworks, nosso trabalho de projetar arquiteturas torna-se reduzido e menos desgastante porque estes são “miniarquiteturas reusáveis que fornecem as estruturas e os comportamentos génericos para uma família de abstrações de software dentro de um contexto”[Pressman, Roger S., Engenharia de Software,McGraw-Hill,2006].
<br/><br/>

<span class="tab"></span>Neste contexto o IBE utiliza padrões arquiteturais a fim de prover uma estrutura genérica para pequenos e médios aplicativos, os quais, focam na entrega rápida de produtos. O framework não tem objetivo substituir grandes ferramentas já consolidadas pelo mercado, ao contrário disso, objetiva ser um arcabouço para desenvolvedores individuais ou penas equipes que precisam de foco em: layouts, requisitos pequenos e bem definidos, entrega rápida de aplicativo, dentre outros. 
                    </p>
                    
                </div>
            </div>
        </div>
        
            
            <div id="rodape"> 
                <div class="container">                    
                    <div  id="autor" class="clear span-24 last">
                        @2012 IBE-Framework | Renan Abreu todos os direitos reservados<br/>
                        Desenvolvido por Renan Abreu 
                        <a href="http://www.renanabreu.com">www.renanabreu.com</a> |renanhabreu@gmail.com |
                    </div>
                </div>
            </div>
    
<div class="over">
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5510469-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    </body>
</html>

