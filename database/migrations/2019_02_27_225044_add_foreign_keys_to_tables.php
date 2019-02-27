<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups');
        });
        Schema::table('group_group_tag', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('group_tag_id')->references('id')->on('group_tags');
        });
        Schema::table('group_student', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('student_id')->references('id')->on('students');
        });
        Schema::table('group_tags', function (Blueprint $table) {
            $table->foreign('group_tag_category')->references('id')->on('group_tag_categories');
        });
        Schema::table('student_student_tag', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('student_tag_id')->references('id')->on('student_tags');
        });
        Schema::table('student_tags', function (Blueprint $table) {
            $table->foreign('student_tag_category')->references('id')->on('student_tag_categories');
        });
        Schema::table('zapier_webhooks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign(['group_id']);
        });
        Schema::table('group_group_tag', function (Blueprint $table) {
            $table->foreign(['group_id']);
            $table->foreign(['group_tag_id']);
        });
        Schema::table('group_student', function (Blueprint $table) {
            $table->foreign(['group_id']);
            $table->foreign(['student_id']);
        });
        Schema::table('group_tags', function (Blueprint $table) {
            $table->foreign(['group_tag_category']);
        });
        Schema::table('student_student_tag', function (Blueprint $table) {
            $table->foreign(['student_id']);
            $table->foreign(['student_tag_id']);
        });
        Schema::table('student_tags', function (Blueprint $table) {
            $table->foreign(['student_tag_category']);
        });
        Schema::table('zapier_webhooks', function (Blueprint $table) {
            $table->foreign(['user_id']);
        });
    }
}
