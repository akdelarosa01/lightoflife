<nav class="navbar navbar-expand-md navbar-dark bg-red sticky-top" id="header-navbar">
    <div class="container">
        <a class="navbar-brand" href="#">Light of Life Christian School</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if (Auth::user()->user_type == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="maintenance" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-wrench"></i> Maintenance
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="maintenance">
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/accounts') }}">
                                    <i class="fa fa-circle-o"></i> Accounts
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/school-year') }}">
                                    <i class="fa fa-circle-o"></i> School Year
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/programs') }}">
                                    <i class="fa fa-circle-o"></i> Year Level
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/sections') }}">
                                    <i class="fa fa-circle-o"></i> Sections
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/departments') }}">
                                    <i class="fa fa-circle-o"></i> Departments
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/subjects') }}">
                                    <i class="fa fa-circle-o"></i> Subjects
                                </a>
                            </li>
                            
                            <li class="dropdown-item">
                                <a href="{{ url('/maintenance/announcement') }}">
                                    <i class="fa fa-circle-o"></i> Announcement
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="transaction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-exchange"></i> Transaction
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="transaction">
                            <li class="dropdown-item">
                                <a href="{{ url('/transaction/subject-to-section') }}">
                                    <i class="fa fa-circle-o"></i> Subject to Section
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/transaction/subject-handle') }}">
                                    <i class="fa fa-circle-o"></i> Subject Handled
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/transaction/enroll-students') }}">
                                    <i class="fa fa-circle-o"></i> Enroll Students
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="monitoring" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-desktop"></i> Monitoring
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="monitoring">
                            <li class="dropdown-item">
                                <a href="{{ url('/monitoring/handouts') }}">
                                    <i class="fa fa-circle-o"></i> Handouts
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/monitoring/activity-logs') }}">
                                    <i class="fa fa-circle-o"></i> Activity Logs
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/monitoring/user-logs') }}">
                                    <i class="fa fa-circle-o"></i> User Logs
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif (Auth::user()->user_type == 2)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="activities" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-wrench"></i> Activities
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="activities">
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/quizzes') }}">
                                    <i class="fa fa-circle-o"></i> Quizes
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/give-quiz') }}">
                                    <i class="fa fa-circle-o"></i> Give Quiz
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/quiz-grade') }}">
                                    <i class="fa fa-circle-o"></i> Quiz Grade
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/item-analysis') }}">
                                    <i class="fa fa-circle-o"></i> Item Analysis
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/homeworks') }}">
                                    <i class="fa fa-circle-o"></i> Homeworks
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/give-homeworks') }}">
                                    <i class="fa fa-circle-o"></i> Give Homeworks
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/activities/homeworks-answer') }}">
                                    <i class="fa fa-circle-o"></i> Student Homework Answers
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/handouts') }}">
                            <i class="fa fa-file-pdf"></i> Handouts
                        </a>
                    </li>
                @elseif (Auth::user()->user_type == 3)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="activities" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-wrench"></i> Activities
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="activities">
                            <li class="dropdown-item">
                                <a href="{{ url('/student-activities/quizzes') }}">
                                    <span id="quiz_count" class="badge badge-danger"></span> Quizes 
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/student-activities/homeworks') }}">
                                    <span id="hw_count" class="badge badge-danger"></span> Homeworks
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif (Auth::user()->user_type == 4)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="activities" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-wrench"></i> Activities
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="activities">
                            <li class="dropdown-item">
                                <a href="{{ url('/parent-activities/quizzes') }}">
                                    <span id="quiz_count" class="badge badge-danger"></span> Quizes 
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('/parent-activities/homeworks') }}">
                                    <span id="hw_count" class="badge badge-danger"></span> Homeworks
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                    


                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/messages/inbox') }}">
                        <i class="fa fa-envelope"></i> Message <span id="msg_count" class="badge badge-danger"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/settings/change-password') }}">
                        <i class="fa fa-cog"></i> Settings
                    </a>
                </li>

                @if (!Auth::guest())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<header class="py-1 bg-image-full header">
    <img class="img-fluid d-block mx-auto" src="{{ asset('/images/logo.png') }}" alt="">
    <p class="text-center">Train up a child in the way he should go, And when he is old he will not depart from it. - Prov 22:6</p>
</header>
