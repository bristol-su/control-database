<?php

namespace App\Providers;

use App\Events\GroupActivated;
use App\Events\GroupCreated;
use App\Events\GroupDeactivated;
use App\Events\GroupTagged;
use App\Events\GroupUntagged;
use App\Events\StudentAddedToGroup;
use App\Events\StudentRemovedFromGroup;
use App\Events\StudentTagged;
use App\Events\StudentUntagged;
use App\Listeners\ZapierWebhooks\AnyAttributeOfAnyGroupUpdated;
use App\Listeners\ZapierWebhooks\AnyGroupActivated;
use App\Listeners\ZapierWebhooks\AnyGroupCreated;
use App\Listeners\ZapierWebhooks\AnyGroupDeactivated;
use App\Listeners\ZapierWebhooks\AnyGroupTaggedWithAnyGroupTag;
use App\Listeners\ZapierWebhooks\AnyGroupTaggedWithAnyTagFromGroupTagCategory;
use App\Listeners\ZapierWebhooks\AnyGroupTaggedWithASpecificGroupTag;
use App\Listeners\ZapierWebhooks\AnyGroupUntaggedFromAnyGroupTag;
use App\Listeners\ZapierWebhooks\AnyGroupUntaggedFromAnyTagFromGroupTagCategory;
use App\Listeners\ZapierWebhooks\AnyGroupUntaggedFromASpecificGroupTag;
use App\Listeners\ZapierWebhooks\AnyStudentTaggedWithAnyStudentTag;
use App\Listeners\ZapierWebhooks\AnyStudentTaggedWithAnyTagFromStudentTagCategory;
use App\Listeners\ZapierWebhooks\AnyStudentTaggedWithASpecificStudentTag;
use App\Listeners\ZapierWebhooks\AnyStudentUntaggedFromAnyStudentTag;
use App\Listeners\ZapierWebhooks\AnyStudentUntaggedFromAnyTagFromStudentTagCategory;
use App\Listeners\ZapierWebhooks\AnyStudentUntaggedFromASpecificStudentTag;
use App\Listeners\ZapierWebhooks\AnyStudentGivenSpecificPosition;
use App\Listeners\ZapierWebhooks\AnyStudentRemovedFromSpecificPosition;
use App\Listeners\ZapierWebhooks\CommitteeMemberAssignedToGroup;
use App\Listeners\ZapierWebhooks\CommitteeMemberRemovedFromGroup;
use App\Listeners\ZapierWebhooks\AnyStudentGivenAnyPosition;
use App\Listeners\ZapierWebhooks\AnyStudentRemovedFromAnyPosition;


use App\Listeners\ZapierWebhooks\SpecificAttributeOfAnyGroupUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'revisionable.saved' => [
            AnyAttributeOfAnyGroupUpdated::class,
            SpecificAttributeOfAnyGroupUpdated::class
        ],
        GroupActivated::class => [
            AnyGroupActivated::class
        ],
        GroupCreated::class => [
            AnyGroupCreated::class
        ],
        GroupDeactivated::class => [
            AnyGroupDeactivated::class
        ],
        GroupTagged::class => [
            AnyGroupTaggedWithAnyGroupTag::class,
            AnyGroupTaggedWithAnyTagFromGroupTagCategory::class,
            AnyGroupTaggedWithASpecificGroupTag::class
        ],
        GroupUntagged::class => [
            AnyGroupUntaggedFromAnyGroupTag::class,
            AnyGroupUntaggedFromAnyTagFromGroupTagCategory::class,
            AnyGroupUntaggedFromASpecificGroupTag::class
        ],
        StudentAddedToGroup::class => [
            CommitteeMemberAssignedToGroup::class
        ],
        StudentRemovedFromGroup::class => [
            CommitteeMemberRemovedFromGroup::class
        ],
        StudentTagged::class => [
            AnyStudentTaggedWithAnyStudentTag::class,
            AnyStudentTaggedWithAnyTagFromStudentTagCategory::class,
            AnyStudentTaggedWithASpecificStudentTag::class,
            AnyStudentGivenSpecificPosition::class,
            AnyStudentGivenAnyPosition::class
        ],
        StudentUntagged::class => [
            AnyStudentUntaggedFromAnyStudentTag::class,
            AnyStudentUntaggedFromAnyTagFromStudentTagCategory::class,
            AnyStudentUntaggedFromASpecificStudentTag::class,
            AnyStudentRemovedFromSpecificPosition::class,
            AnyStudentRemovedFromAnyPosition::class

        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
