# Guide de Configuration et d'Exécution de l'Application

## Prérequis
- PHP 8.2 ou supérieur
- Serveur web Apache
- MySQL ou MariaDB
- Composer (gestionnaire de dépendances PHP)

## Étapes de Configuration

### Cloner le dépôt du projet
```sh
git clone https://github.com/votre-utilisateur/votre-projet.git
cd votre-projet

Installer les dépendances :

composer install

Configurer la base de données :

Créez une base de données MySQL.
Importez le fichier SQL fourni pour créer les tables nécessaires.
Mettez à jour le fichier config.php avec vos informations de connexion à la base de données.
Configurer le serveur web :

Assurez-vous que votre serveur Apache pointe vers le répertoire du projet.
Configurez les permissions nécessaires pour que le serveur web puisse accéder aux fichiers.
Démarrer le serveur web :

Démarrez Apache et assurez-vous qu'il fonctionne correctement.
Exécution de l'Application
Accéder à l'application :

Ouvrez votre navigateur et accédez à http://localhost/votre-projet.
Tester les endpoints API :

Utilisez un outil comme Postman pour tester les différents endpoints de l'API.
Exemple pour ajouter un produit :
Méthode : POST
URL : http://localhost/votre-projet/app/api.php
Corps de la requête (JSON) :

{
    "product_name_en": "Test Product",
    "product_priceUSD": 100,
    "product_stock_units": 10
}


Vérifier les logs :

Consultez les logs pour toute erreur ou information de débogage.
En suivant ces étapes, vous devriez être en mesure de configurer, exécuter et tester votre application de manière sécurisée et efficace.

