## Installation
- Clone the repo `git clone git@github.com:williamoliveira/react-demo-backend.git react-demo-backend`
- Navigate to it `cd react-demo-backend`
- Install dependencies `composer install`
- Copy .env `cp .env.example .env`
- Create your db and set it on .env
- Run migrations and seeds `php artisan migrate --seed`

## Tests
- Set correct APP_URL on .env
- run `phpunit`
