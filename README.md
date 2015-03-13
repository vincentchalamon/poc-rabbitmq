# POC RabbitMQ

Have a look at my slides at [http://slides.com/vincentchalamon/rabbitmq](http://slides.com/vincentchalamon/rabbitmq).

## Getting started

Install project using git:

```
git clone git@github.com:vincentchalamon/poc-rabbitmq.git
```

Install dependencies using [Composer](https://getcomposer.org/):

```
composer install
```

## Consume messages

To download messages through consumers:

```
php app/console rabbitmq:consumer bar
```

## Start RPC server

To use RPC, you need to start server:

```
php app/console rabbitmq:rpc-server bar
```

## Run & tests

To run tests, you should have a test database. You can create it with the following command:

```
php app/console doctrine:database:create --env=test
```

Then launch [RabbitMQ RPC server](#start-rpc-server) and [consumer listener](#consume-messages).

Tests can be launched using `behat`:

```
bin/behat
```

## Docker

To use this POC with Docker, run the following command:

```
docker run -d -p 49153:15672 -p 49154:5672 rabbitmq:3-management
```

Then adapt your `parameters.yml` file with correct `rabbitmq_` parameters:

```yml
parameters:
    ...
    rabbitmq_host: 127.0.0.1
    rabbitmq_port: 49154
    rabbitmq_login: guest
    rabbitmq_password: guest
    rabbitmq_vhost: /
```

**Note: using [Boot2docker](http://boot2docker.io), you need to update `rabbitmq_host` as following:**
```yml
parameters:
    rabbitmq_host: 192.168.59.103
```

Using `rabbitmq:3-management` image, admin interface is available at [http://docker:49153](http://docker:49153).
