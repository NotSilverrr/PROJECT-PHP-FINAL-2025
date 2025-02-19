# docker-php-nginx-mariadb-template

Template for running PHP and MariaDB behind NGINX using Docker, Docker Compose & GNU/Make for orchestration.

## Requirements

- [GNU/Bash](https://www.gnu.org/software/bash/)
- [GNU/Make]()
- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Clone

```bash
git clone https://github.com/aminnairi/docker-php-nginx-mariadb-template my-project
cd my-project
```

## Setup

```bash
cp .env.example .env
```

> *Note: edit the `.env` file to setup your environment.*

## Startup

```bash
make start
```

> *Note: you can change the port listened by the server with the `SERVER_PORT` environment variable by editing it in the `.env` file.*

## Database

```bash
make database
```

> *Note: this will login to your database using a command line interface using your environment setup.*

## Shutdown

```bash
make stop
```

## Restart

```bash
make restart
```

> *Note: this is equivalent to running `make stop start`.*

## PHP

```bash
docker compose exec php php --version
```

> *Note: replace `--version` with the PHP argument of your choice.*

## MariaDB

```bash
docker compose exec mariadb mariadb --version
```

> *Note: replace `--version` with the MariaDB argument of your choice.*


# Framework MVC

## Migrations

Il faut se  connecter au container pour gérer les migration :
```bash
docker exec -it php bash
```

### Lancer les migrations

Exécuter la commande :
```bash
php migrate.php migrate
```

### Rollback les migrations

Exécuter la commande :
```bash
php migrate.php rollback
```

### Afficher la liste des migrations exécutés

Exécuter la commande :
```bash
php migrate.php status
```

### Créer une nouvelle migration

La création de migrations se fait manuellement.
1. Il faut créer un fichier dans Project/Database/Migrations réspectant ce nommage : 
```bash
xxxx_type-de-migration_nom-de-la-table.php
```
2. Le fichier dois ressembler a ca :
```php
<?php
namespace Database\Migrations;

class NomDeLaMigration {
  public function up() {

  }
  public function down() {
    
  }
}
```

## Seeder

Il faut se  connecter au container pour gérer les migration :
```bash
docker exec -it php bash
```

### Lancer le seeder

Exécuter la commande :
```bash
php migrate.php seed [--force]
```

