## установка

```
git clone https://github.com/alexSivka/dist.local.git
cd dist.local
composer install
```

в файле .env прописываем настройки базы

```
php artisan migrate --seed
```

admin

test@test.com / test1234

используется самая простая аутентификация через постоянный токен.