#!/bin/bash

   echo 
   echo "------------------------------------------"
   echo "-      Fabricante ibe-framework 1.1      -"
   echo "------------------------------------------"
   echo -n "Qual o repositorio de aplicacoes? "
   read _rep
   echo -n "Qual o nome da aplicacao? "
   read _app
   criar_aplicacao

executar_outra(){
   
   echo -n "O fabricante? "
   read fab
   
   echo -n "Informe os parametros: "
   read opcao
   
   php "index.php" $fab app:$_app $opcao
   chmod -R 777 $_rep
   mostrar_menu
} 

mostrar_menu(){
   echo 
   echo "------------------------------------------"
   echo "Opções:"
   echo "------------------------------------------"
   echo
   echo "act [ Fabricante de acoes ]"
   echo "ctr [ Fabricante de controladores ]"
   echo "help [ Ajuda ]"
   echo "map [ Fabricante de mapas ]"
   echo "mod [ Fabricante de modulos ]"
   echo "out [ Outro fabricante]"
   echo "quit [ Sair da fabrica ]"
   echo
   echo -n "Qual a opção desejada? "
   read opcao
   echo
   echo "------------------------------------------"
   echo "            Fabricando $opcao             "
   echo "------------------------------------------"
   echo
   case $opcao in
      act) criar_acao ;;
      ctr) criar_controlador ;;
      map) criar_mapa;;
      mod) criar_modulo ;;
      out) executar_outra ;;
      quit) exit ;;
      *) "Opção desconhecida." ; echo ; mostrar_menu ;;
   esac
}

criar_acao(){
   
   echo -n "Qual o nome do modulo? "
   read mod
   
   echo -n "Qual o nome do controlador? "
   read ctr
   
   echo -n "Qual o nome da acao? "
   read act
   
   php "index.php" act app:$_app mod:$mod ctr:$ctr act:$act
   chmod -R 777 $_rep
   mostrar_menu
}


criar_controlador(){

   echo -n "Qual o nome do modulo? "
   read mod
   
   echo -n "Qual o nome do controlador? "
   read ctr
   
   php "index.php" ctr app:$_app mod:$mod ctr:$ctr 
   chmod -R 777 $_rep
   mostrar_menu
}

criar_modulo(){

   echo -n "Qual o nome do modulo? "
   read mod
   
   
   php "index.php" mod app:$_app mod:$mod 
   chmod -R 777 $_rep
   mostrar_menu
}

criar_aplicacao(){
   
   php "index.php" app app:$_app
   chmod -R 777 $_rep
   mostrar_menu
}

criar_mapa(){

   echo -n "Qual o host? "
   read host
   
   echo -n "Qual o usuario? "
   read user
   
   echo -n "Qual a senha? "
   read pass
   
   echo -n "Qual o esquema? "
   read schm
   
   php "index.php" map app:$_app host:$host user:$user pass:$pass schm:$schm
   chmod -R 777 $_rep
   mostrar_menu
}

mostrar_menu

