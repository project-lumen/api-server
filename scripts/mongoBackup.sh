#!/bin/bash
# ------------------------------------------------------------------
# [Jeremie Sophikitis, Adrien Fevre, Victor Anton]
# mongoBackup.sh
# Permet d'exporter la base de donées ou d'importer une existante
# -----------------------------------------------------------------
echo -e "
===========================================
*                                         *
*                                         *
*   MONGO DB EXPORT / IMPORT DBB          *
*                                         *
*                                         *
===========================================
";



read -p "
_______________________________________________________________
| Avez-vous demarrer mongodb ? (sudo service mongod strart)     |
| 1 - Oui                                                       |
| 2 - Non                                                       |
_______________________________________________________________
" choix1;
if [[ $choix1 -eq 1 ]]
then
  echo "Ok super ! On passe a la suite mameneee !" ;
elif [[ $menu -eq 2 ]]
then
  echo "Ah ! Heureusement que je suis là ! " ;
  sudo service mongod start
else
 echo "T'es vraiment un boulet ! c'est pas compliqué : OUI = 1 / NON = 2";
 bash mongoBackup.sh
fi


read -p "
_____________________________
| Que voulez-vous faire ?    |
| 1 - Exporter la BDD        |
| 2 - Importer la BDD        |
| 3 - Sortir du script       |
_____________________________
" choix2;
if [[ $choix2 -eq 1 ]]
then
  read -p "
  _____________________________________________________________
  | Tu as selectionné : Exporter la bdd ! Tu valides ? (y/n)   |
  _____________________________________________________________
  " choix2a;
  if [[ $choix2a -eq y ]]
  then
    echo "Ok j'exporte la bdd dans le dossier data/backup";
    sudo mongodump --out /var/www/html/data/backup/
  elif [[ $choix2a -eq n ]]
  then
    echo "Ah ! Je viens peut etre d'eviter que tu fasses un connerie, non ?";
    bash mongoBackup.sh
  else
    echo "Bordel c'est pas compliqué y pour oui / n pour non ! "
    bash mongoBackup.sh
  fi
elif [[ $choix2 -eq 2 ]]
then
  read -p "
  _____________________________________________________________
  | Tu as selectionné : importer la bdd ! Tu valides ? (y/n)   |
  _____________________________________________________________
  " choix2b;
  if [[ $choix2b -eq y ]]
  then
    echo "Ok j'importe la bdd depuis le dossier data/backup";
    mongorestore --port 27017 /var/www/html/data/backup/
  elif [[ $choix2a -eq n ]]
  then
    echo "Ah ! Je viens peut etre d'eviter que tu fasses un connerie, non ?";
    bash mongoBackup.sh
  else
    echo "Bordel c'est pas compliqué y pour oui / n pour non ! "
    bash mongoBackup.sh
  fi
elif [[ $choix2 -eq 3 ]]
then
  echo "Bye !";
  exit;
fi
