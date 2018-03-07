#!/bin/bash
# ------------------------------------------------------------------
# [Jeremie Sophikitis, Adrien Fevre, Victor Anton]
# mongoBackup.sh
# Permet d'exporter la base de donées ou d'importer une existante
# -----------------------------------------------------------------

# _______________________COLORS_
RED='\033[0;31m'
NC='\033[0m' # No Color
GREEN='\033[0;32m'
On_Black='\033[40m'  # Black
BWhite='\033[1;37m'  # BOLD White
# _______________________________


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
  echo -e "${GREEN}${On_Black}Ok super ! On passe a la suite mameneee !${NC}" ;
elif [[ $menu -eq 2 ]]
then
  echo -e "${GREEN}${On_Black}Ah ! Heureusement que je suis là ! ${NC}" ;
  sudo service mongod start
else
 echo -e "${RED}${On_Black}T'es vraiment un boulet ! c'est pas compliqué : OUI = 1 / NON = 2${NC}";
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
    echo -e "${GREEN}${On_Black}Ok j'exporte la bdd dans le dossier ${BWhite}data/backup${GREEN}${On_Black} et je fais ${BWhite}une copie avec la date / heure d'aujourd'hui ${NC}";
    sudo mongodump --out /var/www/html/data/backup/
    sudo mongodump --out /var/www/html/data/$(date +%Y%m%d_%H:%M:%S)/backup/

  elif [[ $choix2a -eq n ]]
  then
    echo -e "${GREEN}${On_Black}Ah ! Je viens peut etre d'eviter que tu fasses un connerie, non ?${NC}";
    bash mongoBackup.sh
  else
    echo -e "${RED}${On_Black}Bordel c'est pas compliqué y pour oui / n pour non !${NC} "
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
    echo -e "${GREEN}${On_Black} Ok j'importe la bdd depuis le dossier ${BWhite}data/backup ${NC}";
    mongorestore --port 27017 /var/www/html/data/backup/
  elif [[ $choix2a -eq n ]]
  then
    echo -e "${GREEN}${On_Black}Ah ! Je viens peut etre d'eviter que tu fasses un connerie, non ?${NC}";
    bash mongoBackup.sh
  else
    echo -e "${RED}${On_Black}Bordel c'est pas compliqué y pour oui / n pour non ! ${NC}"
    bash mongoBackup.sh
  fi
elif [[ $choix2 -eq 3 ]]
then
  echo -e "${GREEN}${On_Black}Bye !${NC}";
  exit;
fi
