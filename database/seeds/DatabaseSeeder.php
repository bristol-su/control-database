<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        # Get data from UnionCloud
        $unionCloud = resolve('Twigger\UnionCloud\API\UnionCloud');
        $uids = $this->getUids($unionCloud);
        $group_ids = $unionCloud->groups()->getAll()->get()->pluck('id');

        # Create a default user
        factory(App\User::class)->create();

        # Create tags and categories
        factory(App\Models\GroupTagCategory::class, 10)->create()->each(function (\App\Models\GroupTagCategory $cat) {
            $tags = factory(\App\Models\GroupTag::class, 10)->create([
                'group_tag_category' => $cat->id
            ]);
            $cat->tags()->saveMany($tags);
        });
        factory(App\Models\StudentTagCategory::class, 10)->create()->each(function (\App\Models\StudentTagCategory $cat) {
            $tags = factory(\App\Models\StudentTag::class, 10)->create([
                'student_tag_category' => $cat->id
            ]);
            $cat->tags()->saveMany($tags);
        });

        # Create positions
        factory(App\Models\Position::class, 15)->create();

        # Create groups and users, and tag them.
        for($j=0;$j<30;$j++)
        {
            # Create Groups
            $grp = factory(App\Models\Group::class)->create(['unioncloud_id' => array_shift($group_ids)]);

            # Create Students
            for($i=0;$i<7;$i++)
            {
                if(count($uids) === 0)
                {
                    $uids = $this->getUids($unionCloud);
                }
                $student = factory(App\Models\Student::class)->create(['uc_uid' => array_shift($uids)]);

                # Tag the students
                $tags = \App\Models\StudentTag::orderByRaw('RAND()')->take(10)->get();
                $student->tags()->saveMany($tags);

                # Give the students a position
                $position = \App\Models\Position::orderByRaw('RAND()')->get()->first();
                $PosStuGrp = factory(App\Models\PositionStudentGroup::class)->create([
                    'group_id' => $grp->id,
                    'student_id' => $student->id,
                    'position_id' => $position->id
                ]);
//                $student->positions()->saveMany($positions, array_fill(0, count($positions), ['group_id' => $grp->id]));
            }

            # Tag the Group
            $tags = \App\Models\GroupTag::orderByRaw('RAND()')->take(10)->get();
            $grp->tags()->saveMany($tags);

            # Attach accounts to the group
            $grp->accounts()->save(factory(App\Models\Account::class)->make());
            if(rand(0, 100) > 75)
            {
                $grp->accounts()->save(factory(App\Models\Account::class)->make(['is_department_code'=>false]));
            }
        }
    }

    public function getUids(\Twigger\UnionCloud\API\UnionCloud $unionCloud)
    {
        $letter = chr(rand(65,90));
        $uids = [];
        while(count($uids) === 0) {
            $uids = $unionCloud->users()->search(['forename' => $letter])->get()->pluck('uid'); //Search for something with a high number of results
        }
        return $uids;
    }
}
