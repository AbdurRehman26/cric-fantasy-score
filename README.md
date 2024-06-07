# StatusPage

## Local Setup

Copy the `.env.example` to `.env`

Fill it out with your own local setup

Generate a key:

```sh
php artisan key:generate
```

Migrate and setup database

```sh
php artisan migrate --seed
```

The login information will be:

```
user@example.com
password
```

### Run Queues

We use Horizon. So you need to also have a redis running in your local machine.

```sh
php artisan horizon
```

or

```sh
make horizon
```

## Running Tests

You need to setup the test database.

```sh
php artisan migrate --env=testing
```

And then you can run the tests

```sh
php artisan test
```

### Monitoring

Run the following command to start monitoring

```sh
php artisan monitor --frequency=5m
```

This will send a message to the monitoring nodes (the golang apps) to start checking. After they checked, They will send a message back. To process the result you need to listen those messages with the following command:

```sh
php artisan monitor:result-listener
```

or

```sh
make monitor-result
```
