@php
  $accountType = account_type();
@endphp

<div class="vertical-menu">

  <div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
      <!-- Left Menu Start -->
      <ul class="metismenu list-unstyled mt-2" id="side-menu">
        <li>
          <a href="/{{ $accountType }}/dashboard" class="waves-effect">
            <i class="bx bx-home-circle"></i>
            <span key="t-dashboard">Dashboards</span>
          </a>
        </li>

        <li>
          <a href="javascript: void(0);" class="has-arrow waves-effect">
            <i class="bx bx-timer"></i>
            <span key="t-sessions">Session</span>
          </a>
          <ul class="sub-menu" aria-expanded="false">
            <li><a href="/{{ $accountType }}/session/set" key="t-session_set">Activate session</a></li>
            <li><a href="/{{ $accountType }}/session/get/all" key="t-session_all">View all sessions</a></li>
          </ul>
        </li>

        <li class="menu-title" key="t-pages">Pages</li>

        <li>
          <a href="/{{ $accountType }}/my_profile" class="waves-effect">
            <i class="bx bx-user"></i>
            <span key="t-dashboards">My Profile</span>
          </a>
        </li>

        <li>
          <a href="/{{ $accountType }}/password" class="waves-effect">
            <i class="bx bx-key"></i>
            <span key="t-dashboards">Password</span>
          </a>
        </li>

        <li>
          <a href="/logout" class="waves-effect">
            <i class="bx bx-log-out"></i>
            <span key="t-dashboards">Logout</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- Sidebar -->
  </div>
</div>