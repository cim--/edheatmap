<html>
  <head>
	<title>Elite Dangerous Powerplay Support Report</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>      
      
      <h1>Powerplay Support Report</h1>
	
	<p class="error">{{ $sysname }} is not a current Fortified or Stronghold system (or our data is out of date). Please enter a different system.</p>

	<p>Enter the name of a Fortified or Stronghold system to see the Exploited systems it supports.</p>
	
	<form action="{{ route('power.control') }}" method="POST">
	    @csrf
	    <input type="text" name="system"><input type="Submit" value="Get Support Report">
	</form>
	  
  </body>
</html>
