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

=======
# API de Gestion des Produits

Cette application est une API de gestion des produits développée en PHP. Elle permet de créer, lire, mettre à jour et supprimer des produits dans une base de données. L'application utilise une base de données MySQL pour stocker les informations des produits.

## Prérequis

- PHP 7.4 ou supérieur
- MySQL
- Composer (pour la gestion des dépendances PHP)
- Un serveur web (Apache, Nginx, etc.)

## Installation

1. Cloner le dépôt :

    ```bash
    git clone https://github.com/votre-utilisateur/votre-repo.git
    cd votre-repo
    ```

2. Installer les dépendances :

    ```bash
    composer install
    ```

3. Configurer les paramètres de la base de données :

    Modifiez le fichier `config/config.php` pour ajouter vos informations de connexion à la base de données.

    ```php
    <?php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'gestion_produits');
    define('DB_USER', 'votre-utilisateur');
    define('DB_PASS', 'votre-mot-de-passe');
    ```

## Exécution

1. Démarrer le serveur web :

    Si vous utilisez PHP intégré, vous pouvez démarrer le serveur avec la commande suivante :

    ```bash
    php -S localhost:8000 -t public
    ```

2. Accéder à l'API :

    L'API sera accessible à l'adresse suivante : [http://localhost:8000/app/api.php](http://localhost:8000/app/api.php)

## Endpoints de l'API

- `GET /app/api.php` : Récupère tous les produits.
- `GET /app/api.php/{id}` : Récupère un produit par son ID.
- `POST /app/api.php` : Ajoute un nouveau produit.
- `PUT /app/api.php/{id}` : Met à jour un produit existant.
- `DELETE /app/api.php/{id}` : Supprime un produit par son ID.

## Exemple de Requête

### Ajouter un produit

```bash
curl -X POST http://localhost:8000/app/api.php -H "Content-Type: application/json" -d '{
    "product_name_en": "Nouveau Produit",
    "product_priceUSD": 100,
    "product_stock_units": 50
}'
 ```

### Mettre à jour un produit

```bash
curl -X PUT http://localhost:8000/app/api.php/1 -H "Content-Type: application/json" -d '{
    "product_name_en": "Produit Mis à Jour",
    "product_priceUSD": 150,
    "product_stock_units": 30
}'
 ```

### Supprimer un produit

```bash
curl -X DELETE http://localhost:8000/app/api.php/1
```

## Pourquoi et Comment

# Pourquoi

L'objectif de cette application est de fournir une API simple et efficace pour gérer les produits d'une base de données. Elle permet aux développeurs de facilement intégrer des fonctionnalités de gestion de produits dans leurs applications.

# Comment

Configuration de la Base de Données :

- J'ai commencé par définir la structure de la base de données dans un fichier schema.sql.

- Ensuite, j'ai configuré les paramètres de connexion à la base de données dans config/config.php.

# Développement de l'API :

- J'ai créé un fichier api.php qui gère les différentes requêtes HTTP (GET, POST, PUT, DELETE).

- Chaque fonction de l'API (getProducts, getProduct, addProduct, updateProduct, deleteProduct) interagit avec la base de données pour effectuer les opérations CRUD.

# Gestion des Erreurs :

- J'ai ajouté des blocs try-catch pour gérer les exceptions et renvoyer des messages d'erreur appropriés en cas de problème.

# Validation des Données :

- J'ai implémenté une fonction validateProductData pour vérifier que les données des produits sont valides avant de les insérer ou de les mettre à jour dans la base de données.

# Retour des Messages de Confirmation :

- J'ai veillé à ce que chaque opération réussie renvoie un message de confirmation pour informer l'utilisateur de l'état de l'opération.

# Extrait de Code

Voici un extrait de code de la fonction updateProduct et deleteProduct dans api.php :

```bash
<?php
function updateProduct($id) {
    try {
        $pdo = Database::connect();
        $data = json_decode(file_get_contents('php://input'), true);
        if (validateProductData($data)) {
            $stmt = $pdo->prepare('UPDATE wp_k_products SET product_name_en = ?, product_priceUSD = ?, product_stock_units = ? WHERE product_aid = ?');
            $stmt->execute([$data['product_name_en'], $data['product_priceUSD'], $data['product_stock_units'], $id]);
            echo json_encode(['message' => 'Product updated']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to update product', 'error' => $e->getMessage()]);
    }
}

function deleteProduct($id) {
    try {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('DELETE FROM wp_k_products WHERE product_aid = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'Product deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to delete product', 'error' => $e->getMessage()]);
    }
}
```

En suivant ces étapes, j'ai pu développer une API robuste et facile à utiliser pour la gestion des produits.
>>>>>>> d8cc5c92e216e7d2e7fd38b1b25ef3c6862acb84
