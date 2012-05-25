<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>Teste de layout</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print"> 
        <!--[if lt IE 8]>
            <link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection">
        <![endif]-->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/overcast/jquery-ui-1.8.20.custom.css" type="text/css" media="screen, projection">
        
        
        <script src="js/libs/jquery-1.7.2.min.js"></script>
        <script src="js/libs/jquery-ui-1.8.20.custom.min.js"></script>
        <script src="js/libs/modernizr-2.5.3.min.js"></script>
        
        
    </head>
    <body>
        <header>

        </header>
                
        <div role="main" class="container">
              
            <div class="append-6 prepend-6 span-12- last">
                
                <div id="skt-menu">
                    <?php 
                        include_once 'cls/inc_menu.php';
                        
                        Menu::get()->show();
                    ?>
                </div>
                
            </div>
            
        </div>
        
        <footer>

        </footer>

        <script src="js/plugins.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>
