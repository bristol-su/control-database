{{-- unioncloud_uid_to_name column --}}
<span>
	<?php

		$uid = $entry->{$column['name']};

		if ($uid !== null) {

			$user = \Illuminate\Support\Facades\Cache::remember('unioncloud_uid_to_user_column.'.$uid, 100000, function() use ($uid) {
				$unioncloud = resolve(\Twigger\UnionCloud\API\UnionCloud::class);
				try{
					$user = $unioncloud->users()->setMode('basic')->getByUid($uid)->get()->first();
				} catch (Exception $e) {
					return null;
				}
				return $user;
			});

			if($user === null) {
				echo "-";
			} else {
				echo $user->forename . ' ' . $user->surname;
			}


	    } else {
	    	echo "-";
	    }
	?>
</span>
