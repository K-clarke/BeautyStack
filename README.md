# BeautyStack TechTest
This is a simple API 
### Prerequisites

*composer
*mysql

### Installing

configure the database connection information in your root directory `.env` 
```
DATABASE_URL=mysql://{user}:{password}@mysql:{port}/BeautyStack
```
Install Dependancies
```
Composer Install
```
create Database
```
 php bin/console doctine:database:create
```
Run migrations
```
 php bin/console  doctrine:migrations:migrate
```

Run fixtures
```
  php bin/console hautelook:fixtures:load 
```
Start Server
```
 php bin/console server:start
```

To view end points visit 
```
http://127.0.0.1:8000/api/doc

```

To run Behat Tests 

```
vendor/bin/behat
```
