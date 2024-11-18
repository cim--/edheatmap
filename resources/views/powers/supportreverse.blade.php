<html>
  <head>
	<title>Elite Dangerous Powerplay System Support Report</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>
      <h1>Powerplay System Support Report</h1>
	
	<h2>{{ $system->name }}</h2>

	@if ($power) 
	    <p>{{ $system->powerstate }} for {{ $system->power }}</p>
	@else
	    <p>Potential Acquisition Target</p>
	@endif
	
	<table>
	    <thead>
		<tr>
		    <th>Supporting System</th>
		    <th>Power</th>
		    <th>State</th>
		    <th>Distance</th>
		</tr>
	    </thead>
	    <tbody>
		@foreach ($clist as $cname => $csys)
		    <tr>
			<td>{{ $cname }}</td>
			<td>{{ $csys->power }}</td>
			<td>{{ $csys->powerstate }}</td>
			<td>{{ number_format($csys->distance($system),2) }}</td>
		    </tr>
		@endforeach
	    </tbody>
	</table>
	
	
	<h2>New Report</h2>
	<x-powercontrolform />
	  
  </body>
</html>
