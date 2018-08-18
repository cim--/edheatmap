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
  </body>
</html>
