# APP2022JM

Projekt 2022

Instrukcja instalacji:

1. Pobrać repozytorium

2. Zainstalować symfony-cli (https://symfony.com/download)

3. Wykonać polecenie: composer install

4. Zainstalować i uruchomić bazę danych mysql

5. Ustawić prawidłowe dostępy do bazy danych w pliku .env: DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name

6. Uruchomić serwer: symfony server:start

7. Załadować fixtures: php bin/console doctrine:fixtures:load
