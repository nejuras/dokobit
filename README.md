# DOKOBIT

## Project
1. Symfony version 5.4
2. php 8.1
3. mysql 5.7

### Local project set up

1. Download Dokobit project
2. To set up services run `docker-compose up -d --build`

#### Services

- PhpMyAdmin Database client: http://localhost:8081
- Project http://localhost:8080

#### Run tests (PhpUnit)

`php ./vendor/bin/phpunit --testdox`

### Postman
![Screenshot](docs/postman.png)

### Thoughts
1. To implement multiple archiving methods (zip, rar, 7z) I recommend
to use Strategy Design Pattern. 
It lets to decouple archiving methods into different classes and to avoid complex algorithms.
To test different classes much.