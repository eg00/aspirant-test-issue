#Тестовое задание для PHP-программиста

##Описание

Все измененния в ветке develop

Выполнены требования junior и middle

Дополнительные скилы не выполнены

##Установка и запуск

```shell
git clone https://github.com/eg00/aspirant-test-issue.git
git checkout develop
```

###в локальной среде:

```shell
composer install
php -S 0.0.0.0:8080 -t public/
```

###при использовании docker
```shell
docker-compose up -d
docker exec -it aspirant-test-issue_app_1 composer install
```

##Запуск обновления БД и импорт данных

При запуске импорта удаляются все старые записи  и добавляются 10 последних трейлеров из RSS

###в локальной среде:

```shell
php bin/console fetch:trailers
```

###при использовании docker
```shell
docker exec -it aspirant-test-issue_app_1 php bin/console fetch:trailers
```