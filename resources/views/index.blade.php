<html>
  <head>
	<title>Elite Dangerous Traffic Heatmap</title>
	<link rel='stylesheet' href='css/heatmap.css' type='text/css'>
	<script type='text/javascript' src='js/heatmap.js'></script>
  </head>
  <body>
    <table>
	  <tr>
		<td><a href="./?t=hour">Last Hour</a></td>
		<td><a href="./?t=day">Last Day</a></td>
		<td><a href="./?t=week">Last Week</a></td>
		<td colspan='4'><strong>Current</strong>: {{$desc}}</td>
	  </tr>
       <tr>
		 <td><button id='ctrl_forward'>Forward</button></td>
		 <td><button id='ctrl_backward'>Backward</button></td>
		 <td><button id='ctrl_left'>Left</button></td>
		 <td><button id='ctrl_right'>Right</button></td>
		 <td><button id='ctrl_up'>Up</button></td>
		 <td><button id='ctrl_down'>Down</button></td>
		 <td><input id='ctrl_system' value="{{$systems->first()->name}}"><button id='ctrl_go'>Go</button></td>
		 <td><button id='ctrl_reset'>Reset</button></td>
	   </tr>
	 </table>

     <script type='text/javascript'>
	  var systemdata = "{{ $systemdata }}";
	  initHeatmap(THREE, systemdata);
	</script>
  </body>
</html>
