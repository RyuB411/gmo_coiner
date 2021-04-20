# gmo_coiner

# Requirement
docker desktop  
mac  
https://docs.docker.com/docker-for-mac/install/  
win  
https://docs.docker.com/docker-for-windows/install/  

# Installation
command
```
git clone git@github.com:RyuB411/gmo_coiner.git
```
edit gmo_coiner\infra\php\php.ini
```
memory_limit = -1
```
command
```
[mac]cd gmo_coiner
[mac]docker-compose up -d --build
[mac]docker-compose exec app bash
[app]composer install
[app]cp .env.example .env
[app]php artisan key:generate
[app]php artisan migrate
```

edit gmo_coiner\infra\php\php.ini
```
memory_limit = 256
```

command
```
[app]exit
[mac]docker-compose down
[mac]docker-compose up -d
```

access laravel home
```
http://127.0.0.1:8080/
```
access laravel-admin home
```
http://127.0.0.1:8080/admin
```
