# Drink A Lot
A simple webapp to choose and order drinks.

## Installation
Clone the repository https://github.com/fcristiano/drink-a-lot

Create a DB called "awesome_cocktail_bar"
```sql
CREATE DATABASE awesome_cocktail_bar;
```
and set the DB user in config/database.php or in your .env file.

Then run
```bash
composer update
php artisan migrate
```
After the installation the DB will be automatically populated with needed tables and some useful data.

## Usage
After installation launch

```bash
php artisan queue:work database
```
The application needs a daemon to perform some async actions. 

## Playground
The Drink A Lot application has a backend (almost) ready to manage also bartenders and waiters tasks, the essential parts are all implemented.
What is missing is frontend for both waiters and bartenders and the related APIs but core is completed.

So why not play with a simulation? 

If you want to simulate orders processing by not-so-realistic staff you have to run
```bash
php artisan simulation:super-efficient-staff
```  
and then enjoy the efficiency of an overpowered staff!
