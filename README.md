# Tester project for ACMP problems

## Installing
```bash
composer install
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console assets:install --symlink
bower install
```

## Websocket server

```bash
sudo cp tester_websocket.sh /etc/init.d/tester_websocket
sudo chmod +x /etc/init.d/tester_websocket
```

Edit ```/etc/init.d/tester_websocket```

On line 11 change ```/path/to/tester``` to your path to project

To start websocket server:
```bash
sudo /etc/init.d/tester_websocket start
```
To stop websocket server:
```bash
sudo /etc/init.d/tester_websocket stop
```

To be runnable at boot
```bash
sudo update-rc.d tester_websocket defaults
```