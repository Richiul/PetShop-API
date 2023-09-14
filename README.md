# Pet Shop API

This project has the purpose of showing my Laravel programming skills.

To run locally the project you need to first run the following commands

```bash
  git clone https://github.com/Richiul/PetShop-API.git PetShop-API

  cd PetShop-API/
```

After this we need to generate the laravel packages with:

```bash
  composer update
```

It may take some time... so we wait.

## Environment Variables

To run this project, you will need to create your .env file running this command:

```bash
cp .env.example .env
```

I proided a secret key for the app, but if someone want's to use the project in the future needs to generate himself a new key:

`JWT_SECRET` => This should be a random string with letters and numbers

## Database setup

For the database to be created and the basic data to be added, you need to run in the root of your project:

```bash
php artisan migrate:refresh --seed
```

## Start the application

For the routes to work, you need to run in the root of your project:

```bash
php artisan serve
```

Now the application routes should work as expected.

All the routes can be viewed and tested in Swagger importing the Swagger-Editor-File.json file from the root of the project or in Postman importing the Pet-API.postman_collection.json from the root of the project.

## Installation of the custom package

Install package as a local dependency with composer:

```bash
  composer require rpalk/exchange
```

Now you can access the package route in your project:

## API Reference

```http
  GET https://127.0.0.1:8000/api/v1/exchange/currency
```

| Parameter  | Type      | Description                                 |
| :--------- | :-------- | :------------------------------------------ |
| `amount`   | `integer` | **Required**.                               |
| `currency` | `string`  | **Required**. Abbreviation of your currency |

## Running Tests

To run tests in both the project and package, run the following command

```bash
  ./vendor/bin/phpunit
```
