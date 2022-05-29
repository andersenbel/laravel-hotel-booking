## Website of a Hotel with booking on Laravel and bootstrap.

The first page is the list of rooms, with the split on pages, and availability badges above the photos. If a room is available on the current date, the badge is green, else red.

Here, you can also filter rooms by availability in dates diapason and get results only available rooms.

The second page is a room's details page with a description, facilities, room size, price, and the booking form at the bottom.

While the form submits, it validates fields, and if selected diapason is available.

So, how it works, [watch the video](https://www.youtube.com/watch?v=3C38V2Yx2eM).


## Install

### With Docker
Clone the latest version of laravel-hotel-booking app:

    git clone git@github.com:andersenbel/laravel-hotel-booking

Chenge directory:
    cd ./laravel-hotel-booking

Mount the composer image from Docker to the directories you need for your project to avoid the overhead of installing Composer globally:

    docker run --rm -v $(pwd):/app composer install

The `-v` and `--rm` options of docker run create a virtual container that binds to the current directory until it is removed. The contents of your `~/laravel-hotel-booking` directory will be copied to the container, and the contents of the vendor folder created by Composer inside the container will be copied to the current directory.


Set permission level such that it is owned by a non-root user:

    sudo chown -R $USER:$USER ~/laravel-app


Set environment:

    cp .env.example .env
    nano .env

Find section  DB_CONNECTION and update system setup. You will change the following fields:

    DB_CONNECTION=mysql
    DB_HOST=dbhistory 
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=your_mysql_root_password

Start containers, create volumes and set up and connect networks:

    docker-compose up -d

Generate Lavarel's key:generate and config:cache:

    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan config:cache


Start application:

    http://localhost/

Start PHPMyAdmin for data manage:

    http://localhost:8080/

Do not forget to start the migration of the database and populate it from `rooms.sql`:

    docker-compose exec app php artisan migrate