<html>
    <head>
        <title>IBEFramework v1.0.0</title>
        <style type="text/css">
            #ibe-content{ margin: 10px auto; width:640px;border:1px solid #ccc; padding: 5px;}
            input { border:1px solid #2f2f2f; padding:10px;width: 630px;}
            .btn-submit{ width:90px;float:right; cursor:pointer}
            p{ font-family: sans-serif; text-transform: uppercase; font-size: 11px;margin-top: 35px;}
            h5{ color:royalblue; }
        </style>
    </head>
    <body>
        <div id="ibe-content">

            <pre>
              _   _    ____     ____              ____   U _____ u____
     ___     | \ |"|  / __"| uU|  _"\ u  ___   U |  _"\ u\| ___"|/  _"\
    |_"_|   <|  \| |><\___ \/ \| |_) |/ |_"_|   \| |_) |/ |  _|"/| | | |
     | |    U| |\  |u u___) |  |  __/    | |     |  _ <   | |___U| |_| |\
   U/| |\u   |_| \_|  |____/>> |_|     U/| |\u   |_| \_\  |_____||____/ u
.-,_|___|_,-.||   \\,-.)(  (__)||>>_.-,_|___|_,-.//   \\_ <<   >> |||_
 \_)-' '-(_/ (_")  (_/(__)    (__)__)\_)-' '-(_/(__)  (__)__) (__)__)_)

   ____  U _____ u    _       _   _ _____               _____    _   _    _
U | __")u\| ___"|/U  /"\  uU |"|u| |_ " _|     ___     |" ___|U |"|u| |  |"|
 \|  _ \/ |  _|"   \/ _ \/  \| |\| | | |      |_"_|   U| |_  u \| |\| |U | | u
  | |_) | | |___   / ___ \   | |_| |/| |\      | |    \|  _|/   | |_| | \| |/__
  |____/  |_____| /_/   \_\ <<\___/u |_|U    U/| |\u   |_|     <<\___/   |_____|
 _|| \\_  <<   >>  \\    >>(__) )( _// \\_.-,_|___|_,-.)(\\,- (__) )(    //  \\
(__) (__)(__) (__)(__)  (__)   (__)__) (__)\_)-' '-(_/(__)(_/     (__)  (_")("_)

U _____ u _    U _____ u   ____      _     _   _     _____
\| ___"|/|"|   \| ___"|/U /"___|uU  /"\  u| \ |"|   |_ " _|
 |  _|"U | | u  |  _|"  \| |  _ / \/ _ \/<|  \| |>    | |
 | |___ \| |/__ | |___   | |_| |  / ___ \U| |\  |u   /| |\
 |_____| |_____||_____|   \____| /_/   \_\|_| \_|   u |_|U
 <<   >> //  \\ <<   >>   _)(|_   \\    >>||   \\,-._// \\_
            </pre>
            <h4>Welcome to the builder IBE Framework 1.0.0</h4>
            <?php if(isset($_GET['builded']) && $_GET['builded'] == 'true'): ?>
                <h5>Builded Application</h5>
            <?php endif;?>
            <div>
                <form action="skt/index.php" method="POST">
                    <label>
                        <p>Host</p>
                        <input type="text" name="host" />
                    </label>
                    <label>
                        <p>User</p>
                        <input type="text" name="user" />
                    </label>
                    <label>
                        <p>Pass</p>
                        <input type="text" name="pass" />
                    </label>
                    <label>
                        <p>Schema</p>
                        <input type="text" name="schema" />
                    </label>
                    <p>
                        <input type="submit" value="Gerar App" class="btn-submit"/>
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>