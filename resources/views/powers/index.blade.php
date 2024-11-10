<html>
  <head>
	<title>Elite Dangerous Powerplay Tools</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
	<h1>Powerplay Tools</h1>

	<h2>System Support Report</h2>

	<p>Enter the name of a Fortified or Stronghold system to see the Exploited systems it supports.</p>
	
	<form action="{{ route('power.control') }}" method="POST">
	    @csrf
	    <input type="text" name="system"><input type="Submit" value="Get Support Report">
	</form>

	<h2>Overall Power Maps</h2>

	<p>Schematics of Power connectivity</p>

	<p><a href="{{ route('power.loose') }}">Check for loose systems</a> - these may indicate data errors - and <a href="{{ route('power.dataage' }}">check current data age</a>.</p>
	
	<table>
	    <thead>
		<tr>
		    <th>Week</th>
		    <th>Starting Date</th>
		    @foreach ($powers as $power => $home)
			<th>{{ $power }}</th>
		    @endforeach
		</tr>
	    </thead>
	    <tbody>
	    @for ($i=$week ; $i>=0 ; $i--)
		<tr>
		    <td>{{ $i }}</td>
		    <td>{{ Carbon\Carbon::parse("2024-10-24")->addWeeks($i)->format("j F Y") }}</td>
		    @foreach ($powers as $power => $home)
			<td>
			    @if ($i == 0 && $power == "Nakato Kaine")
				-
			    @else
			    <a href="{{ route('power.week', [$power, $i]) }}" title="{{ $power }} week {{ $i }}">
				Show
			    </a>
			    @endif
			</td>
		    @endforeach
		</tr>
	    @endfor
	    </tbody>
	</table>
	  
  </body>
</html>
