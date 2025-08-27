# 🚀 Basket challange

A Test to create baskets with products on a RestFul API.

It´s made with Symfony 7.3. 

![PHP Version](https://img.shields.io/badge/PHP-8.2-blue?style=flat-square&logo=php)
![Symfony](https://img.shields.io/badge/Symfony-000000?style=flat-square&logo=Symfony&logoColor=white)

## ✨ Challenge

Wir würden gerne sehen, inwiefern du eine einfache REST-API eines Warenkorbes in PHP mit Symfony (ohne API-Platform) umsetzt. Die API muss RESTful sein und folgende Funktionen bieten:

- Einen Warenkorb anlegen
- Einen Artikel in den Warenkorb legen
- Einen Artikel aus dem Warenkorb löschen 
- Einen Artikel im Warenkorb editieren 
- Den Warenkorb anzeigen lassen

## 🚀 Quick Start

Before beginning with the installation, you will need the following

- PHP >= 8.2 
- Composer

### Clone the repository

- Open your terminal or command prompt
- Navigate to the directory where you want to save the project
- Use the git clone command followed by the repository URL

```
git clone git@github.com:mapfeiffer/basket-challange.git
cd basket-challange
```

### Install composer packages

```
composer install
```

### Run DB setup. Includes creating products

```
composer setup-local-db
```

### Using

- Start the local server with the following command

```
symfony server:start
```

- You can use Postman or any other REST client to test the API
- No authentication is required

## 🧪 Testing

### Run PHPUnit test

```
composer test:phpunit
```

## 🧹 Code style and static code analysis etc. 

### Run PHP Peck, Parallel-Lint, PHP CS Fixer, PHPStan and tests 

```
composer analyse-and-test
```

### Run PHP CS Fixer

```
composer run:php-cs-fixer
```

### Run PHPStan

```
composer run:phpstan
```

### Run Parallel-lint

```
composer run:php-parallel-lint
```

### Run Peck

```
composer run:peck
```
