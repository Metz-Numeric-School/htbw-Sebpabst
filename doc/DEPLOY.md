# Procédure de Déploiement

Décrivez ci-dessous votre procédure de déploiement en détaillant chacune des étapes. De la préparation du VPS à la méthodologie de déploiement continu.

## Préparation du VPS

- Je récupère le projet github et je le clone :
`git clone https://github.com/Metz-Numeric-School/htbw-Sebpabst.git`

- Je lance le projet en local : 
`php bin/serve`

- Pour éviter qu'il y ait des erreurs et pour installer les dépendances, je fais la commande:
`composer install --optimize-autoloader`

- Je vais sur localhost:85 pour vérifier que le projet fonctionne.

- Je lance la commande ci-dessous pour lancer le programme du fichier "create-database" pour créer la base de données sur phpmyadmin :
`php bin/create-database`

- Je lance aussi le programme du fichier demo_data.sql pour charger les données de démonstration:
`php bin/load-demo-data`

- Je lance phpmyadmin avec cette commande :
`sudo systemctl start mysql`

- Je vais sur phpmyadmin et je me connecte à la bdd grâce aux identifiants dans le fichier ".env".
<!-- DB_HOST="localhost"
DB_PORT="3306"
DB_DATABASE="habit_tracker"
DB_USERNAME="root"
DB_PASSWORD="" -->

- J'initialise Git Cliff dans mon projet :
`git cliff --init`

La commande va créer un fichier cliff.

- Je crée un fichier CHANGELOG.md :
`git cliff -o CHANGELOG.md`

- Je versionne le CHANGELOG.md et pour ça je dois le faire de cette manière :
X.X.X
    - Le premier X c’est le majeur, il signifie que l'on déploie l'application et qu'il y   a beaucoup de changements.
    - Le deuxième X c’est le mineur, quand on ajoute des fonctionnalités
    - Le troisème X c’est le patch, quand on résout un bug

- Je crée un tag :
`git tag 0.1.0`
`git push origin 0.1.0`

- Je fais mes commit si besoin :
`git add .`
`git commit -m "feat: nouvelle feature"`
`git push`

- Je commit le fichier CHANGELOG.md :
`git add CHANGELOG.md`
`git commit -m "version 0.1.0`

- Et je push tout :
`git push origin "0.1.0"`

- Si l'on souhaite imposer une version majeur à git cliff :
`git cliff --tag 1.0.0 -o CHANGELOG.md`


## Méthode de déploiement

- Je me connecte sur mon VPS :
`ssh root@192.168.23.141`

- Si ce n'est pas déjà fait, j'installe Aapanel :
`URL=https://www.aapanel.com/script/install_7.0_en.sh && if [ -f /usr/bin/curl ];then curl -ksSO "$URL" ;else wget --no-check-certificate -O install_7.0_en.sh "$URL";fi;bash install_7.0_en.sh aapanel`

- Je note les identifiants que l'on me génère dans un bloc-notes :
aaPanel Internet Address: https://178.251.86.104:30315/bd2f09f3
aaPanel Internal Address: https://192.168.23.141:30315/bd2f09f3
username: 5n9nm9yr
password: 6cd0a1b1

- Je vais sur l'adresse Internet et je connecte avec les identifiants

- Je choisis de faire un serveur Nginx

- Je clique sur "one click" et j'attends que l'installation soit faite.

- Je clique sur "website" et sur "add site".

- Dans le formulaire, je rentre le nom de domaine dans "domain name"
    - Au niveau du dossier (www/wwwroot/) j'ajoute un nom de dossier.
    - Dans Database, je mets MySQL
    - Je décoche Create HTML file
    - Je clique sur confirm

- Une fois que c'est créé, ça nous génère un identifiant et un mot de passe que je note dans mon bloc-notes :
    database : sql_pabst_dfs_lan
    password : 38818b83b472b

- Je fais un script de déploiement pour pointer l’ip vers le serveur
    - Je vais à la racine de la vm debian donc je vais dans le dossier var 
    `cd /var`
    - Je crée le dépot git :
    `mkdir depot_git`
    - Je fais une commande git pour faire un remote :
    `git init --bare`

Je retourne en local sur VScode :
- J'ajoute le remote au VPS
`git remote add vps root@192.168.23.141:/var/depot_git:`

- Je push ou main en fonction de comment je l’ai nommé
`git push -u vps main`
    
    - Si j'ai une erreur quand je push, j'effectue ces 2 commandes :
    sudo chown -R root:root /var/depot_git
    sudo chmod -R 755 /var/depot_git

- Je crée un tag :
`git tag 1.0.0`
- Et je le push :
`git push vps tag 1.0.0`

- Dans ma VM Debian, je fais cette commande :
`git --git-dir=/var/depot_git --work-tree=/www/wwwroot/pabst checkout -f 1.0.0`


- Je retourne sur Aapanel, je clique sur mon site -> Directory -> Running Directory -> /public
Et je sauvegarde

- Je vais dans website, dans Composer et je clique sur "execute" sans toucher a rien

- Je vais dans "Files", je vais sur ce chemin :
/www/wwwroot/pabst

- Je vais dans le fichier .env et je remplace par les identifiant phpmyadmin que j'ai noté précédemment

- Je vais dans l'onglet "Databases", puis sur "PhpMyAdmin" et sur "Public Access"
Et la j'arrive sur le PhpMyAdmin de Aapanel et je rentre les identifiants de connexion que l'on m'a généré sur Aapanel.

Si phpmyadmin ne s'ouvre pas car Nginx ne démarre pas, dans le VPS, j'execute cette commande :
`sudo systemctl restart nginx && sudo systemctl status nginx`

- J'importe la base de données du projet que je récupère dans database.sql, sauf que je retire les deux premières lignes :
`CREATE DATABASE IF NOT EXISTS habit_tracker;` 
`USE habit_tracker;`

- Je copie le reste du code et je le colle dans l'onglet "SQL" sur phpmyadmin. Je clique sur "Format" pour formater le reste et je clique sur "Execute".
J'ai maintenant la base de données qui est prête.

- Je vais dans la liste de sites sur Aapanel, je clique sur mon site, je l'ouvre et c'est bon,

Mon site est déployé !

- Si il y a une erreur Nginx quand je navigue sur une autre page, je vais sur Aapanel, sur mon site et je vais dans "Config" et à la ligne 27 dans "location", je le change en:
 location / {
    try_files $uri $uri/ /index.php$is_args$args;
}