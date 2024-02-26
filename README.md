### Symfony 6 Fixing DB issue

Репозиторий с уже готовой конфигурацией для быстрого старта. Для запуска выполните следующие действия:

1. Сколнируйте репозиторий себе 
```shell
git clone https://github.com/endemio/php-symfony-6-example.git symfony-command-fix-db-fs --branch command-fix-db-fs
```
где **symfony-command-fix-db-fs** - папка, в которую хотите скопировать проект

2. Скопируйте docker-compose и env
```shell
cp docker-compose-dev.yml docker-compose.yml 
cp .env.example .env
```

3. Заполните .env
```shell
APP_ENV=dev
APP_SECRET=lubayadlinnaystroka
DATABASE_URL="mysql://user:login@host:port/db_name?serverVersion=8.0"
```
где все, кроме `mysql` и `serverVersion` задается из реальных данных, **lubayadlinnaystroka* - любая длинная строка без пробелов и специальныхз символов

4. Запускаем контейнер
```shell
docker-compose build
docker-compose run app composer install
docker-compose up -d
```

5. Записывем данные в базу из п.3

6. Запускаем симфони-команды в контейнере
```shell
docker-compose exec app bash
```

7. Проверяем список *Пользователей* 
```shell
ubuntu@ab23f1c78255:/var/www$ bin/console app:show-entities Clients
╔════╤════════════╤═══════════╗
║ Id │ First_name │ Last_name ║
╟────┼────────────┼───────────╢
║ 1  │ Иван       │ Иванов    ║
║ 2  │ Василиса   │ Краснова  ║
╚════╧════════════╧═══════════╝
```
и *Сессий*

```shell
ubuntu@ab23f1c78255:/var/www$ bin/console app:show-entities Sessions
╔════╤═════════════════════╤══════════════════════════╗
║ Id │ Start_time          │ Session_configuration_id ║
╟────┼─────────────────────┼──────────────────────────╢
║ 1  │ 2023-08-21 17:00:00 │ 1                        ║
║ 2  │ 2023-08-28 17:00:00 │ 1                        ║
║ 3  │ 2023-08-22 17:00:00 │ 2                        ║
║ 4  │ 2023-08-29 17:00:00 │ 2                        ║
║ 5  │ 2023-08-21 17:00:00 │ 1                        ║
║ 6  │ 2023-08-22 17:00:00 │ 2                        ║
║ 7  │ 2023-08-22 17:00:00 │ 2                        ║
╚════╧═════════════════════╧══════════════════════════╝
```
8. Запускаем исправление базы
```shell
ubuntu@ab23f1c78255:/var/www$ bin/console app:db-fix
==== Original ====
╔════╤═════════════════════╤══════════════════════════╗
║ Id │ Start_time          │ Session_configuration_id ║
╟────┼─────────────────────┼──────────────────────────╢
║ 1  │ 2023-08-21 17:00:00 │ 1                        ║
║ 2  │ 2023-08-28 17:00:00 │ 1                        ║
║ 3  │ 2023-08-22 17:00:00 │ 2                        ║
║ 4  │ 2023-08-29 17:00:00 │ 2                        ║
╚════╧═════════════════════╧══════════════════════════╝

==== Duplicates ====
╔════╤═════════════════════╤══════════════════════════╗
║ Id │ Start_time          │ Session_configuration_id ║
╟────┼─────────────────────┼──────────────────────────╢
║ 1  │ 2023-08-21 17:00:00 │ 1                        ║
║ 3  │ 2023-08-22 17:00:00 │ 2                        ║
║ 3  │ 2023-08-22 17:00:00 │ 2                        ║
╚════╧═════════════════════╧══════════════════════════╝
```
Получаем 2 таблицы с Сессиями которые оригиналы или были дубликатами, а также исправляются записи в таблице `session_members` 