<html>
  <head>
	<title>Elite Dangerous Powerplay System Support Report</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>
      <h1>Powerplay System Support Report</h1>
	
	<h2>{{ $system->name }}</h2>

	<p>{{ $system->powerstate }} for {{ $system->power }}</p>
	
	<table>
	    <thead>
		<tr><th>Exploited System</th>
		    <th>Other Supporters (Strongholds bold)</th></tr>
	    </thead>
	    <tbody>
		@foreach ($exlist as $exname => $fortlist)
		    <tr>
			<td>{{ $exname }}</td>
			<td>
			    @foreach ($fortlist as $fortsys)
				@if ($fortsys->powerstate == "Stronghold")
				    <strong>{{ $fortsys->name }}</strong>
				@else
				    {{ $fortsys->name }}
				@endif
				;
			    @endforeach
			</td>
		    </tr>
		@endforeach
	    </tbody>
	</table>
	
	
	<h2>New Report</h2>

	<p>Enter the name of a Fortified or Stronghold system to see the Exploited systems it supports.</p>
	
	<form action="{{ route('power.control') }}" method="POST">
	    @csrf
	    <input type="text" name="system"><input type="Submit" value="Get Support Report">
	</form>

	  
  </body>
</html>
