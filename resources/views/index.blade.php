<html>
  <head>
	<title>Elite Dangerous Traffic Heatmap</title>
	<link rel='stylesheet' href='css/heatmap.css' type='text/css'>
	<script type='text/javascript' src='js/heatmap.js'></script>
  </head>
  <body>
    <table>
	  <tr>
		<td>Grey: 0</td>
		<td>Red: 1-2</td>
		<td>Orange: 3-9</td>
		<td>Yellow: 10-99</td>
		<td>White: 100-249</td>
		<td>Cyan: 250-999</td>
		<td>Blue: 1000+</td>
	  </tr>
	  <tr>
		<td><a href="./?t=hour">Last Hour</a></td>
		<td><a href="./?t=day">Last Day</a></td>
		<td><a href="./?t=week">Last Week</a></td>
		<td colspan='2'><strong>Current</strong>: {{$desc}}</td>
		<td colspan='2'><input id='ctrl_system' value="{{$systems->first()->name}}"><button id='ctrl_go'>Go</button></td>
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

     <script type='text/javascript'>
	  var systemdata = "{{ $systemdata }}";
	  initHeatmap(THREE, systemdata);
	</script>
  </body>
</html>
