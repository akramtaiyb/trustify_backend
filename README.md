# Trustify

Trustify est une plateforme de vérification des faits qui permet aux utilisateurs de publier des informations, de voter sur leur véracité et de commenter. Le but est de réduire la propagation des fausses informations en utilisant un système de vote communautaire et un système de réputation.

## Fonctionnalités

- Publication d'articles par les utilisateurs
- Vote sur les articles pour les classer comme "real" ou "fake"
- Commentaires sur les articles
- Système de réputation des utilisateurs basé sur leurs interactions
- Classement des utilisateurs comme experts lorsqu'ils atteignent un certain score de réputation

## Captures d'écran

### Page d'accueil
![Page d'accueil](/screenshots/home.png)

### Page de journal
![Page de journal](/screenshots/journal_1.png)
![Page de journal](/screenshots/journal_2.png)

### Page de profil
![Page de profil](/screenshots/profile.png)

## Cas d'utilisation

1. **Inscription et Connexion**
    - Les utilisateurs peuvent s'inscrire et se connecter pour accéder à toutes les fonctionnalités de la plateforme.

2. **Publication d'articles**
    - Une fois connectés, les utilisateurs peuvent publier des articles en fournissant un titre et un contenu.

3. **Vote sur les articles**
    - Les utilisateurs peuvent voter pour classer un article comme "real" ou "fake". Les experts ont un poids plus important dans leurs votes.

4. **Commentaires sur les articles**
    - Les utilisateurs peuvent commenter les articles pour discuter de leur contenu et fournir des preuves supplémentaires.

5. **Système de réputation**
    - Les utilisateurs gagnent des points de réputation en fonction de leurs interactions sur la plateforme. Les utilisateurs atteignant 1000 points deviennent des experts.

## Instructions pour démarrer le projet

### Prérequis

- Node.js (v18.19.1)
- npm (v10.2.4) ou yarn
- PHP v8.1
- Composer v2.4.1
- MySQL v8.0

### Démarrage du back-end

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/akramtaiyb/trustify_backend.git
    cd trustify_backend
    ```

2. Configurez l'environnement :
    ```bash
    cp .env.example .env
    ```
   Modifiez le fichier `.env` avec les informations de votre base de données.

3. Installez les dépendances PHP :
    ```bash
    composer install
    ```

4. Générez la clé de l'application :
    ```bash
    php artisan key:generate
    ```

5. Exécutez les migrations et les seeders :
    ```bash
    php artisan migrate --seed
    ```

6. Démarrez le serveur de développement :
    ```bash
    php artisan serve
    ```

### Démarrage du front-end

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/akramtaiyb/trustify_frontend.git
    ```
   
2. Accédez au dossier front-end :
    ```bash
    cd trustify_frontend
    ```

3. Installez les dépendances :
    ```bash
    npm install
    ```
   ou
    ```bash
    yarn install
    ```

4. Démarrez le serveur de développement :
    ```bash
    npm run dev
    ```
   ou
    ```bash
    yarn run dev
    ```

## Credentials de Connexion pour Tester

Utilisez les credentials suivants pour vous connecter et tester l'application :

- **Email**: craigsilverman@trustify.com
- **Mot de passe**: letsfightfakenews2024

## Remerciements

Ce projet a été développé par une équipe d'étudiants dévoués :

- AMTOUG Abdessamad
- OUHAJRA Abderahim
- LAMRIKHI Abdessamad
- TAIYB Akram

Nous tenons à exprimer notre gratitude particulière à notre professeur, Mr. **BOUSALEM Zakaria**, pour son soutien et ses conseils inestimables tout au long de ce projet. Merci de nous avoir inspirés et guidés.
