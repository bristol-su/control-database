<div class="user-panel">
  <a class="pull-left image" href="{{ route('backpack.account.info') }}">
    <img src="{{ backpack_avatar_url(backpack_auth()->user()) }}" class="img-circle" alt="User Image">
  </a>
  <div class="pull-left info">
    <p><a href="{{ route('backpack.account.info') }}">{{ backpack_auth()->user()->name }}</a></p>
    <small><small><a href="{{ route('backpack.account.info') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a> &nbsp;  &nbsp; <a href="#" onclick="$('#logout_form').submit()"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></small></small>
  </div>
  <form action="{{ backpack_url('logout') }}" method="POST" id="logout_form">@csrf</form>
</div>

