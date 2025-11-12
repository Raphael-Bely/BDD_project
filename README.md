# BDD_project — Guide d'installation PostgreSQL

Ce fichier explique rapidement les commandes pour configurer et se connecter à la base de données PostgreSQL.

## Prérequis
- Installer PostgreSQL sur la machine.
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

## Se connecter à la base avec psql
Pour se connecter à la base nouvellement créée :
```bash
psql -U $DB_USER -d $DB_NAME
```

## Suppreimer la base de données
Pour supprimer la base de données :
```bash
dropdb -U $DB_USER $DB_NAME
```

## Fichier d'exemple `.env.example`
Conserver ce fichier comme modèle :
```env
DB_NAME=nom_de_ta_base
DB_USER=ton_utilisateur
DB_PASS=motdepasse
DB_HOST=localhost
DB_PORT=5432
```