<html>
  <head>
	<title>Elite Dangerous Traffic History</title>
	<link rel='stylesheet' href='css/heatmap.css' type='text/css'>
	<script type='text/javascript' src='js/heatmap.js'></script>
  </head>
  <body>
	<h1>Traffic History</h1>

{!! $chart->render() !!}
     
	<p>History based on data retrieved from EDDN.</p>

	<p>At the moment, "Powerplay" counts pledged commanders in PP-controlled systems. Unpledged CMDRs, and pledged CMDRs in neutral/acquisition systems, don't count. This may change with later updates to the Journal.</p>
	
	<form action="/history" method="get">
	    Start: <input type="date" name="start" value="{{$start->format("Y-m-d")}}">
	    End: <input type="date" name="end" value="{{$end->format("Y-m-d")}}">
	    <input type="submit" value="Set Date Range">
	</form>
  </body>
</html>
