<html>
  <head>
	<title>Elite Dangerous Traffic Heatmap</title>
	<link rel='stylesheet' href='css/heatmap.css' type='text/css'>
	<script type='text/javascript' src='js/heatmap.js'></script>
  </head>
  <body>
	<h1>Traffic Heatmap</h1>
	<table>
	    @if ($mode == "colonisation")
		<tr>
		    <td></td>
		    <td colspan="2">Red: claimed</td>
		    <td colspan="2">Yellow: new</td>
		    <td colspan="2">White: old</td>
		</tr>
	    @else
	  <tr>
		<td>Grey: 0</td>
		<td>Red: 1-2</td>
		<td>Orange: 3-9</td>
		<td>Yellow: 10-99</td>
		<td>White: 100-249</td>
		<td>Cyan: 250-999</td>
		<td>Blue: 1000+</td>
	  </tr>
	    @endif
	  <tr>
		<td><a href="./?t=hour">Last Hour</a></td>
		<td><a href="./?t=day">Last Day</a></td>
		<td><a href="./?t=week">Last Week</a></td>
		<td><a href="./?m=colonisation">Colonisation</a></td>
		<td><strong>Current</strong>: {{$desc}} ({{$total}})</td>
		<td colspan='2'><input id='ctrl_system' value="{{$first}}"><button id='ctrl_go'>Go</button></td>
	  </tr>
       <tr>
		 <td><button id='ctrl_forward'>Forward</button></td>
		 <td><button id='ctrl_backward'>Backward</button></td>
		 <td><button id='ctrl_left'>Left</button></td>
		 <td><button id='ctrl_right'>Right</button></td>
		 <td><button id='ctrl_up'>Up</button></td>
		 <td><button id='ctrl_down'>Down</button></td>
		 <td><button id='ctrl_reset'>Reset</button></td>
	   </tr>
	 </table>

	<div id='mapcontainer'>
	    <script type='text/javascript'>
	     var systemdata = "{{ $systemdata }}";
	     var mode = "{{ $mode }}";
	     initHeatmap(THREE, mode, systemdata);
	    </script>
	</div>

	<p>Heatmap based on data retrieved from EDDN. Busiest hundred systems named.</p>
  </body>
</html>
