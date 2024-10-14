# API RESTful pour la gestion des produits Kalstein Plus

## Description
Cette application permet de gérer des produits via une API RESTful. Elle inclut des fonctionnalités pour ajouter, modifier, supprimer et lister les produits. L'application est développée en PHP et MySQL.

## Fonctionnalités implémentées
### Partie 1 : API RESTful
- **GET /api/produits** : Liste tous les produits.
- **GET /api/produits/{id}** : Affiche les détails d’un produit spécifique.
- **POST /api/produits** : Ajoute un nouveau produit.  
  - Validation : Le nom du produit est obligatoire, le prix doit être un nombre positif, et le stock un entier non négatif.
- **PUT /api/produits/{id}** : Met à jour un produit existant.
- **DELETE /api/produits/{id}** : Supprime un produit.

### Partie 2 : Interface Utilisateur
- **Formulaire HTML** : Pour ajouter ou modifier un produit.
- **Tableau de produits** : Liste les produits avec options de modification et suppression.

### Partie 3 : Sécurité et Bonnes Pratiques
- **Protection contre les injections SQL** : Utilisation de requêtes préparées.
- **Validations côté client** : Vérifications des champs du formulaire via JavaScript (nom, prix, stock).

## Fonctionnalités non terminées
- **Authentification et gestion des rôles (Partie 4)** : Cette partie n'a pas pu être complétée dans le temps imparti. Voici l'approche que j'envisageais :
  - Implémentation d'une authentification basique via JSON Web Tokens (JWT).
  - Mise en place d'une gestion des rôles (administrateur, utilisateur) pour restreindre l'accès à certaines actions.

## Installation et exécution
1. Clonez ce dépôt.
2. Configurez votre base de données MySQL et mettez à jour les paramètres dans le fichier `config.php`.
3. Exécutez `composer install` pour installer les dépendances (si nécessaire).
4. Lancez l'application via votre serveur local (par exemple, XAMPP, WAMP).

## Améliorations futures
- Compléter le système d'authentification.
- Ajouter plus de tests unitaires pour chaque endpoint.
- Finaliser la gestion des rôles et permissions.