<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearColumnToPositionStudentGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('position_student_group', function (Blueprint $table) {
            $table->year('committee_year')->default('default')->after('position_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('position_student_group', function (Blueprint $table) {
            $table->dropColumn('committee_year');
        });
    }
}
