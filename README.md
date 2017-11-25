.
=
Tester project for ACMP problems
```
composer install
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console assets:install --symlink
bower install
```