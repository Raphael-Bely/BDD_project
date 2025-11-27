# BDD_project — Guide d'installation PostgreSQL

Ce fichier explique rapidement les commandes pour configurer et se connecter à la base de données PostgreSQL. Toutes ces étapes sont également réalisables via le Makefile fourni, qui automatise les commandes et charge automatiquement le fichier `.env` si présent.

## Prérequis
- Installer PostgreSQL sur la machine.
- Installer Post
- Disposer d'un utilisateur PostgreSQL existant (ou disposer des droits pour en créer un).
- Créer un fichier `.env` contenant les variables d'environnement (voir section suivante).

## Configuration de l'environnement
1. Copier le fichier d'exemple et remplir les valeurs :
```bash
cp .env.example .env
# Éditer .env et renseigner :
# DB_NAME=nom_de_ta_base
# DB_USER=ton_utilisateur
# DB_PASS=motdepasse
# DB_HOST=localhost
# DB_PORT=5432
```

2. Charger les variables d'environnement dans la session shell :
```bash
source .env
```
Après `source .env` : les variables $DB_NAME, $DB_USER, $DB_PASS, $DB_HOST, $DB_PORT sont disponibles dans le terminal.

**Alternativement :** Le Makefile charge automatiquement `.env` si le fichier existe. Pas besoin de `source .env` manuellement — lancer directement les commandes Make.

## Créer la base de données
Pour créer la base avec l'utilisateur défini :
```bash
createdb -U $DB_USER $DB_NAME
```
- Si l'utilisateur `$DB_USER` n'existe pas, le créer :
```bash
# création d'un rôle avec mot de passe
createuser -P $DB_USER
# puis créer la base :
createdb -U $DB_USER $DB_NAME
```

**Avec le Makefile :**
```bash
make createdb
```
Cela crée la base en utilisant les variables de `.env` (chargées automatiquement).

## Se connecter à la base avec psql
Pour se connecter à la base nouvellement créée :
```bash
psql -U $DB_USER -d $DB_NAME
```

**Avec le Makefile :**
```bash
make psql
```
Cela ouvre un shell psql connecté à la base.

## Supprimer la base de données
Pour supprimer la base de données :
```bash
dropdb -U $DB_USER $DB_NAME
```

**Avec le Makefile :**
```bash
make dropdb
```
Cela supprime la base de données.

## Fichier d'exemple `.env.example`
Conserver ce fichier comme modèle :
```env
DB_NAME=nom_de_ta_base
DB_USER=ton_utilisateur
DB_PASS=motdepasse
DB_HOST=localhost
DB_PORT=5432
```

## Remarques sur le Makefile
- Le Makefile inclut les cibles `createdb`, `dropdb`, `psql`, `create`, `insert`, `select`, `update` pour exécuter les fichiers SQL correspondants.
- Il utilise les variables de `.env` ou les valeurs par défaut si le fichier n'existe pas.
- Pour une liste complète des cibles : `make help` (si ajouté au Makefile).
- Exemple d'utilisation : `make createdb && make create && make insert` pour initialiser la base et insérer des données.