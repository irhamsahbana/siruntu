<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->index('course_master_id');
            $table->foreign('course_master_id')
            ->references('id')->on('course_masters');

            $table->index('semester_id');
            $table->foreign('semester_id')
            ->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['course_master_id']);
            $table->dropIndex(['course_master_id']);

            $table->dropForeign(['semester_id']);
            $table->dropIndex(['semester_id']);
        });
    }
}
