<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trip_id')->unsigned()->index();
            $table->bigInteger('plan_id')->unsigned()->nullable()->index();
            // Owner
            $table->bigInteger('user_id')->unsigned()->index();

            /* DATA */
            $table->string('title', 100);
            $table->date('date');
            $table->text('description')->nullable();
            $table->boolean('is_locked')->default(false);

            /* VISIBILITY */
            $table->tinyInteger('visibility')->unsigned()->index();
            $table->timestamp('published_at')->useCurrent();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('trip_id')
                    ->references('id')
                    ->on('trips');

            $table->foreign('plan_id')
                    ->references('id')
                    ->on('plans')
                    ->onDelete('set null');

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
