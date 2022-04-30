<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterClassroomParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_participants', function (Blueprint $table) {
            $table->index('course_id');
            $table->foreign('course_id')
            ->references('id')->on('courses');

            $table->index('classroom_id');
            $table->foreign('classroom_id')
            ->references('id')->on('classrooms');

            $table->index('person_id');
            $table->foreign('person_id')
            ->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classroom_participants', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropIndex(['course_id']);

            $table->dropForeign(['classroom_id']);
            $table->dropIndex(['classroom_id']);

            $table->dropForeign(['person_id']);
            $table->dropIndex(['person_id']);
        });
    }
}
