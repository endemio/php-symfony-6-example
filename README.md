### Symfony 6 Twig Global example

Файлы для статьи [Получение списка всех роутов](https://dev.endemic.ru/article/all-routes)

Репозиторий с уже готовой конфигурацией для быстрого старта. Для запуска выполните следеющие действия

1. Сколнируйте репозиторий себе
```shell
git clone https://github.com/endemio/php-symfony-6-example.git php-symfony-git-test --branch routes-all-routes 
```
где **php-symfony-git-test** - папка, в которую хотите скопировать проект

2. Скопируйте docker-compose и env
```shell
cp docker-compose-dev.yml docker-compose.yml 
cp .env.example .env
```

3. Заполните .env
```shell
APP_ENV=dev
APP_SECRET=lubayadlinnaystroka
```
**lubayadlinnaystroka* - любая длинная строка без пробелов и специальныхз символов

4. Запускаем контейнер
```shell
docker-compose build
docker-compose up -d
```

Все ОК, заходим теперь в консоль `docker-compose exec app bash` и запускаем команду `php bin/console app:all-routes`


