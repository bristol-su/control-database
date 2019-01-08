<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
{{--<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>--}}
{{--<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>--}}
<li class="treeview">
    <a href="#"><i class="fa fa-smile-o"></i> <span>Groups</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href='{{ backpack_url('group') }}'><i class='fa fa-tag'></i> <span>Groups</span></a></li>
        <li><a href='{{ backpack_url('account') }}'><i class='fa fa-tag'></i> <span>Accounts</span></a></li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-smile-o"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href='{{ backpack_url('student') }}'><i class='fa fa-tag'></i> <span>Students</span></a></li>
    </ul>
</li>


<li class="treeview">
    <a href="#"><i class="fa fa-smile-o"></i> <span>Tags</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">


        <li class="treeview">
            <a href="#"><i class="fa fa-smile-o"></i> <span>Group</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href='{{ backpack_url('group_tag_category') }}'><i class='fa fa-tag'></i> <span>Group Tag Categories</span></a></li>
                <li><a href='{{ backpack_url('group_tag') }}'><i class='fa fa-tag'></i> <span>Group Tags</span></a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#"><i class="fa fa-smile-o"></i> <span>Student</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href='{{ backpack_url('student_tag_category') }}'><i class='fa fa-tag'></i> <span>Student Tag Categories</span></a></li>
                <li><a href='{{ backpack_url('student_tag') }}'><i class='fa fa-tag'></i> <span>Student Tags</span></a></li>
            </ul>
        </li>
    </ul>
</li>
