<div class="deznav">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<li class="nav-label first">Main Menu</li>
			@if(auth()->user()->ROLE == 1)
			<li><a href="{{ url('admin/timetable') }}" aria-expanded="false">
					<i class="fa fa-calendar-o" aria-hidden="true"></i>
					<span class="nav-text">Timetable</span>
				</a>
			</li>
			<li><a href="{{ url('admin/reschedule') }}" aria-expanded="false">
					<i class="fa fa-refresh" aria-hidden="true"></i>
					<span class="nav-text">Reschedule Activity</span>
				</a>
			</li>
			<li><a href="{{ url('admin/calendar') }}" aria-expanded="false">
					<i class="fa fa-calendar" aria-hidden="true"></i>
					<span class="nav-text">Calendar</span>
				</a>
			</li>
			@elseif(auth()->user()->ROLE == 2)
			<li><a href="{{ url('pic/timetable') }}" aria-expanded="false">
					<i class="fa fa-calendar-o" aria-hidden="true"></i>
					<span class="nav-text">Timetable</span>
				</a>
			</li>
			<li><a href="{{ url('pic/progress') }}" aria-expanded="false">
			<i class="fa fa-briefcase" aria-hidden="true"></i>
					<span class="nav-text">Progress</span>
				</a>
			</li>
			@elseif(auth()->user()->ROLE == 3)
			<li><a href="{{ url('investor/timetable') }}" aria-expanded="false">
					<i class="fa fa-calendar-o" aria-hidden="true"></i>
					<span class="nav-text">Timetable</span>
				</a>
			</li>
			<li><a href="{{ url('investor/calendar') }}" aria-expanded="false">
					<i class="fa fa-calendar" aria-hidden="true"></i>
					<span class="nav-text">Calendar</span>
				</a>
			</li>
			@endif
			@if(auth()->user()->ROLE == 1)
			<li class="nav-label">Data Master</li>
			<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
					<i class="fa fa-user-o" aria-hidden="true"></i>
					<span class="nav-text">Users</span>
				</a>
				<ul aria-expanded="false">
					<li><a href="{!! url('/admin/user-pic'); !!}">PIC</a></li>
					<li><a href="{!! url('/admin/user-investor'); !!}">Investor</a></li>
				</ul>
			</li>
			<li><a href="{{ url('admin/category') }}" aria-expanded="false">
					<i class="fa fa-filter" aria-hidden="true"></i>
					<span class="nav-text">Category</span>
				</a>
			</li>
			@endif
		</ul>
	</div>
</div>