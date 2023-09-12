
# Money Exchanger

This package was developed for foreign clients, in case they want to see the prices on the page in a specific currency.



## Installation

Install package with composer

```bash
  composer require rpalk/exchange
  composer install
```


    
## Running Tests

To run tests, run the following command

```bash
  ./vendor/bin/phpunit
```


## API Reference

```http
  GET {{base_url}}/exchange/currency
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `amount` | `integer` | **Required**. |
| `currency` | `string` | **Required**. Abbreviation of your currency |


