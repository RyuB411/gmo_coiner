# gmo_coiner
![image](https://user-images.githubusercontent.com/39729800/117288490-84c15900-aea6-11eb-99f1-d37203c8053f.png)
![image](https://user-images.githubusercontent.com/39729800/117411342-6d3dab00-af4e-11eb-9b6c-5911be54d1c5.png)

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

command
```
[mac]cd gmo_coiner
[mac]docker-compose up -d --build
[mac]docker-compose exec app bash
[app]composer install
[app]npm run dev
[app]cp .env.example .env
[app]php artisan key:generate
[app]php artisan migrate
```

edit gmo_coiner\infra\php\php.ini
```
memory_limit = 1028
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
