<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('report_id')->unsigned()->nullable()->index();
            $table->nullableMorphs('locationable');
            // Owner
            $table->bigInteger('user_id')->unsigned()->index();

            /* DATA */
            $table->boolean('is_draft')->default(true);
            $table->text('content')->nullable();

            $table->string('image', 100)->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            /* VISIBILITY */
            $table->tinyInteger('visibility')->unsigned()->index();
            $table->timestamp('published_at')->useCurrent();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('report_id')
                    ->references('id')
                    ->on('reports')
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
        Schema::dropIfExists('sections');
    }
}
