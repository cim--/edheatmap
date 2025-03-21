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

	<p>Between November 2024 and 20 March 2025, "Powerplay" counts pledged commanders in PP-controlled systems. Unpledged CMDRs, and pledged CMDRs in neutral/acquisition systems, don't count.</p>

	<p>On and after 20 March 2025, unpledged CMDRs should report the same as pledged ones, and detection of Acquisition systems becomes possible. There is expected to be a transitional period as senders are updated.</p>
	
	<form action="/history" method="get">
	    Start: <input type="date" name="start" value="{{$start->format("Y-m-d")}}">
	    End: <input type="date" name="end" value="{{$end->format("Y-m-d")}}">
	    <input type="submit" value="Set Date Range">
	</form>
  </body>
</html>
