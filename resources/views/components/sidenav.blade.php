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

        @if (Auth::user()->account_type == 'Super Admin' || Auth::user()->account_type == 'Admin')
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

          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
              <i class="bx bx-receipt"></i>
              <span key="t-subjects">O'level Subjects</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
              <li><a href="/{{ $accountType }}/subject/add" key="t-subject_add">Add subject</a></li>
              <li><a href="/{{ $accountType }}/subject/list" key="t-subject_all">View all subjects</a></li>
            </ul>
          </li>

          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
              <i class="bx bxs-school"></i>
              <span key="t-facultys">Faculty</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
              <li><a href="/{{ $accountType }}/faculty/add" key="t-faculty_add">Add faculty</a></li>
              <li><a href="/{{ $accountType }}/faculty/list" key="t-faculty_all">View all faculties</a></li>
            </ul>
          </li>

          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
              <i class="bx bx-collection"></i>
              <span key="t-programmes">Programme</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
              <li><a href="/{{ $accountType }}/programme/add" key="t-programme_add">Add Programme</a></li>
              <li><a href="/{{ $accountType }}/programme/upload" key="t-programme_upload">Upload Programme</a></li>
              <li><a href="/{{ $accountType }}/programme/list" key="t-programme_all">View all Programmes</a></li>
            </ul>
          </li>

          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
              <i class="bx bx-handicap"></i>
              <span key="t-catchment_elds">Catchment & ELDS</span>
            </a>
            <ul class="sub-menu" aria-expanded="true">
              <li>
                <a href="javascript: void(0);" class="has-arrow" key="t-level-1-2">ELDS</a>
                <ul class="sub-menu" aria-expanded="true">
                  <li><a href="/{{ $accountType }}/elds/add" key="t-elds_add">Add ELDS</a></li>
                  <li><a href="/{{ $accountType }}/elds/list" key="t-elds_all">View all ELDS</a></li>
                </ul>
              </li>
              <li>
                <a href="javascript: void(0);" class="has-arrow" key="t-level-1-2">Catchment</a>
                <ul class="sub-menu" aria-expanded="true">
                  <li><a href="/{{ $accountType }}/catchment/add" key="t-catchment_add">Add catchment</a></li>
                  <li><a href="/{{ $accountType }}/catchment/list" key="t-catchment_all">View all catchment</a></li>
                </ul>
              </li>
            </ul>
          </li>

          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
              <i class="bx bx-edit"></i>
              <span key="t-admission_settings">Admission</span>
            </a>
            <ul class="sub-menu" aria-expanded="true">
              <li>
                <a href="javascript: void(0);" class="has-arrow" key="t-level-1-2">Candidates</a>
                <ul class="sub-menu" aria-expanded="true">
                  <li><a href="/{{ $accountType }}/candidate/upload" key="t-candidate_add">Upload & View</a></li>
                  <li><a href="/{{ $accountType }}/candidate/delete" key="t-candidate_all">Delete By Session</a></li>
                </ul>
              </li>
              <li>
                <a href="javascript: void(0);" class="has-arrow" key="t-level-1-2">Admission</a>
                <ul class="sub-menu" aria-expanded="true">
                  <li><a href="/{{ $accountType }}/admission/criteria/update" key="t-admission_criteria">Criteria
                      Settings</a></li>
                  <li><a href="/{{ $accountType }}/admission/generate" key="t-admission_generate">Generate
                      Admission</a>
                  </li>
                  <li><a href="/{{ $accountType }}/admission/statistics" key="t-admission_stat">Admission
                      Statistics</a>
                  </li>
                  <li><a href="/{{ $accountType }}/admission/discretion/upload" key="t-admission_stat">Upload
                      Discretion</a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          @if (Auth::user()->account_type == 'Super Admin')
            <li>
              <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-user-detail"></i>
                <span key="t-users">Users</span>
              </a>
              <ul class="sub-menu" aria-expanded="false">
                <li><a href="/{{ $accountType }}/user/register" key="t-user_add">Add User</a></li>
                <li><a href="/{{ $accountType }}/user/list" key="t-user_all">View All Users</a></li>
              </ul>
            </li>
          @endif
        @endif


        @if (Auth::user()->account_type == 'Dean')
          <li>
            <a href="/{{ $accountType }}/candidates" class="waves-effect">
              <i class="bx bx-user"></i>
              <span key="t-candidates">Candidates</span>
            </a>
          </li>
          <li>
            <a href="/{{ $accountType }}/admission/view" class="waves-effect">
              <i class="bx bx-edit"></i>
              <span key="t-admission_generate">View Admission</span>
            </a>
          </li>
          <li>
            <a href="/{{ $accountType }}/admission/statistics" class="waves-effect">
              <i class="bx bx-bar-chart"></i>
              <span key="t-admission_stat">Admission Statistics</span>
            </a>
          </li>
        @endif

        <li class="menu-title" key="t-pages">Settings</li>

        <li>
          <a href="/{{ $accountType }}/password" class="waves-effect">
            <i class="bx bx-key"></i>
            <span key="t-dashboards">Password</span>
          </a>
        </li>

        <li>
          <a href="/logout" class="waves-effect">
            <i class="bx bx-power-off"></i>
            <span key="t-dashboards">Logout</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- Sidebar -->
  </div>
</div>
