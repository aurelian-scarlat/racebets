# Hello

The container is based on the official PHP docker. Inside it I installed composer and Slim framework.
It was actually a good opportunity for me to try it for the first time. I won't do it again.

The database is actually SQLite, because it's been a long time since I haven't tried it.

I solved the concurrency issue, which was actually the most important part of the test, in 3 ways:
* With versions (see App\Models\User) and the persistence will fail if the entity is stale
* With SQL transactions for deposits and bonuses - insert the deposit and the bonus in the same transaction, 
  which will do a lock and prevent inserting other deposits until this one is finished
* A combination of both - for withdrawals

Validation is missing - I'm using strong typing and database constraints which is fine in development mode.

## Installation

**1. Build the container**

`docker build -t racebets .`

**2. Run the container**

`docker run --rm --name racebets -p 80:80 racebets`

and then you can go to [http://localhost/]()

**3. Run the container and modify files**

`docker run --rm --name racebets -p 80:80 -v $(pwd):/var/www/html racebets`

**4. Get inside the container**

`docker exec -it racebets /bin/bash`

## API Endpoints

**`POST http://localhost/users`** 
```
{
  "email": "jane@doe.com",
  "firstName": "Jane",
  "lastName": "Doe",
  "country": "RU",
  "gender": "f"
}
```

**`PATCH http://localhost/users/{id}`** 
```
{
  "email": "jane@doe.com",
  "firstName": "Jane",
  "lastName": "Doe",
  "country": "RU",
  "gender": "f"
}
```
**`GET http://localhost/reports?start=2018-01-01`**

Start parameter is optional

**`POST http://localhost/deposits`**
```
{
    "userId": 2,
    "amount": -20
}
```

**`POST http://localhost/withdrawals`**
```
{
    "userId": 2,
    "amount": -20
}
```
