<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->string('position_name');
            $table->year('committee_year');
            $table->unsignedInteger('role_id');
            $table->timestamps();
            $table->softDeletes();
        });

        $positionStudentGroups = \App\Models\Role::withTrashed()->get();
        foreach($positionStudentGroups as $psg) {

                $role = \Illuminate\Support\Facades\DB::table('role')
                    ->where('position_id', $psg->position_id)
                    ->where('group_id', $psg->group_id)
                    ->first();
                if(!$role) {
                    \Illuminate\Support\Facades\DB::table('role')
                        ->insert([
                            'position_id' => $psg->position_id,
                            'group_id' => $psg->group_id,
                            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
                        ]);
                    $role = \Illuminate\Support\Facades\DB::table('role')
                        ->where('position_id', $psg->position_id)
                        ->where('group_id', $psg->group_id)
                        ->first();
                }
            \Illuminate\Support\Facades\DB::table('user_role')
                ->insert([
                    'student_id' => $psg->student_id,
                    'position_name' => $psg->position_name,
                    'committee_year' => $psg->committee_year,
                    'role_id' => $role->id,
                    'created_at' => $psg->created_at,
                    'updated_at' => $psg->updated_at,
                    'deleted_at' => $psg->deleted_at
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role');
    }
}
