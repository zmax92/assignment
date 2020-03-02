Coding assignment for showcasing my skills and expertise. 

## Description

- Create a list of books, with the following functions,
    - Add a book to the list.
    - Delete a book from the list.
    - Change an authors name
    - Sort by title or author
    - Search for a book by title or author
    - Export the the following in CSV and XML
        - A list with Title and Author
        - A list with only Titles
        - A list with only Authors

## Installation

- Clone this repo to your local machine
```bash
$ git clone https://github.com/zmax92/assignment.git
$ cd assignment/
```
- Copy .env.example file to .env file
```bash
$ cp .env.example .env
```
- Apply changes to lines in .env file
```php
ln 5: APP_URL=http://localhost
ln 9: DB_CONNECTION=mysql
```
to 
```php
ln 5: APP_URL=http://127.0.0.1:8000
ln 9: DB_CONNECTION=sqlite
```
and remove all other DB_ lines
```php
ln 10: DB_HOST=127.0.0.1
ln 11: DB_PORT=3306
ln 12: DB_DATABASE=laravel
ln 13: DB_USERNAME=root
ln 14: DB_PASSWORD=
```
- install composer packages
```bash
$ composer install
```
- install npm packages
```bash
$ npm install
```
- create databases
```bash
$ touch database/database.sqlite
```
- Create database tables from migration files and populate them with initial data
```bash
$ php artisan migrate:fresh --seed
```
- Generate application encryption key 
```bash
$ php artisan key:generate
```
## Deployment
Run app on Local Development Server
```bash
$ php artisan serv
```
This will launch Laravel development server on http://127.0.0.1:8000, and in browser will be greeted with

![Screenshot browser](http://assignment.zmaher.com/images/screenshot.png)

## Running the tests
To run tests, there must be Google Chrome and [ChromeDriver](https://laravel.com/docs/6.x/dusk#managing-chromedriver-installations) installed

- Run all existing tests
```bash
$ php artisan serv
$ php artisan dusk
```
> (Optional) filter specific group of tests or specific feature test
```bash
$ php artisan dusk --filter BooksBrowserTest

$ php artisan dusk --filter testFrontendCreationOfBook
$ php artisan dusk --filter testFrontendDeletionOfBook
$ php artisan dusk --filter testFrontendUpdateAuthor
```
## Demo
[assignment.zmaher.com](http://assignment.zmaher.com/)
