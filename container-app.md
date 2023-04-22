### Create network for application 
```podman network create loginsite```

### Create login app from prepared docker file
```podman run --name login-app -d -p 10080:80 -d --network loginsite -e MYSQL_ROOT_PASSWORD=qwerty@1234 -e MYSQL_HOST=mysql -e MYSQL_DATABASE=logowanie quay.io/okonal/login-app:test```

### Create database from upstream image 
```podman run -d -p 3306:3306 --network loginsite --network-alias mysql --name mysql -e MYSQL_ROOT_PASSWORD=qwerty@1234 -e MYSQL_DATABASE=logowanie -e MYSQL_USER=test -e MYSQL_PASSWORD=test mysql```


### Network connection tests
```podman run -it --network loginsite nicolaka/netshoot```

### Run from container to install mysqli
```docker exec -ti <your-php-container> /bin/bash```
>> docker-php-ext-install mysqli 
>> docker-php-ext-enable mysqli
>> apachectl restart

### Add to docker file with login app to auto install mysqli
```RUN docker-php-ext-install mysqli```

### test connection with database with curl command
```curl http://localhost:10080/connect.php```