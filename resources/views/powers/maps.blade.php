<!DOCTYPE html>
<html>
  <head>
	<title>Elite Dangerous Powerplay Maps: {{ $power }}, Week {{ $week }}</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>


      <h1>Powerplay Maps: {{ $power }} week {{ $week }}</h1>

	<p>Powerplay maps are intended to give a schematic outline of the Power's connections between Fortified and Stronghold systems, and therefore its positional strengths and weaknesses. They do not attempt to replicate the positions of the systems in 3-D space - systems nearby on these maps may be very far apart in reality, and vice versa.</p>

	<p>Maps are generated daily based on EDDN data and may therefore be inaccurate in some details.</p>
	
	<h2>Hierarchy Map</h2>

	<p>The hierarchy map shows the shortest connected routes from the Headquarters to each Fortified or Stronghold system. Disconnected systems are shown at their approximate distance vertically, but horizontal position is arbitrary.</p>

	<div><img src="/storage/{{ $hierarchy }}" alt="Hierarchy Map"></div>

	<h2>Connectivity Map</h2>

	<p>The connectivity map shows all connections between Fortified and Stronghold systems, and indicates shared Exploited systems. The relative position of disconnected groups or systems is arbitrary.</p>

	<div><img src="/storage/{{ $connection }}" alt="Connectivity Map"></div>

	
	
  </body>
</html>
