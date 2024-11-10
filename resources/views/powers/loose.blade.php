<html>
  <head>
	<title>Elite Dangerous Powerplay Tools - Loose System Check</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>

      <h1>Loose Systems</h1>
	<p>This finds Exploited systems in the database which don't seem to be in range of a Fortified or Stronghold system. This may indicate a missing supporter, or it may be that their supporter has been lost and they should have reverted to Neutral but this hasn't been detected yet.</p>

	@foreach ($powers as $power => $home)
	    <h2>{{ $power }}</h2>

	    @if (count($loose[$power]) == 0)
		<p>Looks okay</p>
	    @else
		<ul>
		    @foreach ($loose[$power] as $system)
			<li>{{ $system->name }} ({{ $system->x }}, {{ $system->y }}, {{ $system->z }})</li>
		    @endforeach
		</ul>
	    @endif

	@endforeach
	
  </body>
</html>
