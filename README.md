# aSeeder
Export your complete database to laravel seeder files. No need to specify any extra commands.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/aseeder.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/aseeder)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/aseeder.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/aseeder)

## Install 
```
    composer require acfbentveld/aseeder
```
Or add `"acfbentveld/aseeder": "^1.0",` to your composer file.

> Recommended is to remove every file in your seeds folder. Inclusive the `DatabaseSeeder.php`. This file will be generated if it does not exists.

## Usage
Using this package is very simple. Run the command `php artisan make:aseed`.
Some actions require a confirm from the user.

### Actions
When running the command, a few actions requires confirmation from the user.

##### Where do you want to store the seed files?
This means you can specify where you want to seeds to be stored. By default it's the default `default/seeds` folder. Use relative paths only!

##### Create seeds for all tables?
This means you can seed allt he tables in your database default this is true. If you wish the specify the tables, press `n`.

##### Truncate before seeding?
If you choose this option `true` a new line to your seed file will be added `\DB::table('%TABLE%')->truncate();`. This means when you run the seeds, your table will be truncated first.


