<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
        Schema::create('cinema_seating_master', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('seat_no_from');
            $table->string('seat_no_to');
            $table->timestamps();
        });
        
        /*Sample Data
        {
            id: 1,
            name: Premiuim/Gold/Silver,
            seat_no_from: 1,
            seat_no_to: 50,
            created_at: '2022-10-10',
            updated_at: null
        } */       

        Schema::create('cinema', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('city');
            $table->integer('total_seats');
            $table->string('seating_type'); // Comma separted values of cinema_seating_master
            $table->timestamps();
        });

        /*Sample Data
        {
            id: 1,
            name: PVR/INOX,
            city: LA/Berlin,
            total_seats: 200,
            seating_type: 1,2
            created_at: '2022-10-10'
            updated_at: null
        } */

        Schema::create('movies', function($table) {
            $table->increments('id');
            $table->string('movie_name');
            $table->text('description');
            $table->timestamps();
        });

        /*Sample Data
        {
            id: 1,
            movie_name: Titanic/Inception,
            description: This movie is blah blah...,
            created_at: '2022-10-10'
            updated_at: null
        } */

        Schema::create('movie_shows', function($table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->integer('cinema_id')->unsigned();
            $table->foreign('cinema_id')->references('id')->on('cinema');
            $table->time('show_start_time');
            $table->time('show_end_time');
            $table->timestamps();
        });

        /*Sample Data
        {
            id: 1,
            movie_id: 1,
            cinema_id: 1,
            show_start_time: 2:00:00,
            show_end_time: 5:00:00,
            created_at: '2022-10-10'
            updated_at: null
        } */

        Schema::create('movie_show_pricing', function($table) {
            $table->increments('id');
            $table->integer('movie_show_id')->unsigned();
            $table->foreign('movie_show_id')->references('id')->on('movie_shows');
            $table->integer('cinema_master_id')->unsigned();
            $table->foreign('cinema_master_id')->references('id')->on('cinema');
            $table->float('price',20,2);
            $table->timestamps();
        });

        /*Sample Data
        {
            id: 1,
            movie_show_id: 1,
            cinema_master_id: 1,
            price: 500.00,
            created_at: '2022-10-10'
            updated_at: null
        } */

        Schema::create('booking', function($table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('movie_show_id')->unsigned();
            $table->foreign('movie_show_id')->references('id')->on('movie_shows');
            $table->integer('movie_show_pricing_id')->unsigned();
            $table->foreign('movie_show_pricing_id')->references('id')->on('movie_show_pricing');
            $table->date('date');
            $table->integer('seat_no');
            $table->timestamps();
        });

        /*Sample Data
        {
            id: 1,
            user_id: 1,
            movie_show_id: 1,
            movie_show_pricing_id: 1,
            date: '2022-10-10',
            seat_no: 34,
            created_at: '2022-10-10'
            updated_at: null
        } */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
