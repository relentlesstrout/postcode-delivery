# Postcode Delivery
A simple app that allows users to see stores that deliver to their postcode

![Screenshot](public/screenshot.png)

## Features
- User can search for stores by postcode
- User can add a new store via create form
- Api route to return stores near to a postcode
- Api route to return stores that can deliver to a postcode
- Console command to download and import UK postcodes

## Prerequisites

Before you begin, ensure you have the following installed on your machine:
- PHP 8.2 or higher
- Composer
- Node.js
- NPM
- sqlite3

## Installation
1. Clone the repository
```bash
git clone https://github.com/relentlesstrout/postcode-delivery
```
2. Navigate to the project folder
```bash
cd postcode-delivery
```
3. Install dependencies
```bash
composer install
```
4. Install Node dependencies
```bash
npm install
```
5. Copy the environment file and configure it
```bash
cp .env.example .env
```
6. Generate your app key
```bash
php artisan key:generate
```
7. Run the Database migration
```bash
php artisan app:fetch-postcodes
```
8. Run the postcode import command
```bash
php artisan app:fetch-postcodes
```
9. Seed the database
```bash
php artisan db:seed
```
10. Start the npm server
```bash
npm run dev
```
11. Start the server
```bash
php artisan serve
```

You should now be able to access the app at http://localhost:8000


