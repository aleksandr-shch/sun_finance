# sunFinance
API test assignment

Realized with Symfony 5.2, Api platform bundle and Docker (php8, posgresql13, nginx)

After cloning there is the docker folder in the root of the project. 
preferences inside of - `docker/docker-compose.yaml`

From this docker folder initiate building with this command - `docker-compose build`
After that you can run containers - `docker-compose up -d`

.env with default setup DB connection like that - `DATABASE_URL="postgresql://sun_finance:sun_finance@postgres:5432/sun_finance?serverVersion=13&charset=utf8"`

composer run - `docker-compose run php composer install`

To run migration - `docker-compose run php bin/console doctrine:migrations:migrate`

Fixtures to fill the DB - `docker-compose run php bin/console doctrine:fixtures:load`

Beginning tests - `docker-compose run php bin/phpunit`