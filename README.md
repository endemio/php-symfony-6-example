### Symfony 6 Twig Streamed response

Файлы для статьи [Использование потоков в Twig](https://dev.endemic.ru/article/twig-streamed-response)

Репозиторий с уже готовой конфигурацией для быстрого старта. Для запуска выполните следеющие действия

1. Сколнируйте репозиторий себе 
```shell
git clone https://github.com/endemio/php-symfony-6-example.git php-symfony-git-test --branch twig-streamed-response 
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

Все ОК, проверяем теперь [тут](http://0.0.0.0:8080/streamed)


