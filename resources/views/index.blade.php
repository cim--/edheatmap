<html>
  <head>
	<title>Elite Dangerous Traffic Heatmap</title>
	<link rel='stylesheet' href='css/heatmap.css' type='text/css'>
	<script type='text/javascript' src='js/heatmap.js'></script>
  </head>
  <body>
     <table>
       <tr>
		 <td><button id='ctrl_forward'>Forward</button></td>
		 <td><button id='ctrl_backward'>Backward</button></td>
		 <td><button id='ctrl_left'>Left</button></td>
		 <td><button id='ctrl_right'>Right</button></td>
		 <td><button id='ctrl_up'>Up</button></td>
		 <td><button id='ctrl_down'>Down</button></td>
	   </tr>
	 </table>

     <script type='text/javascript'>
	  var systemdata = "{{ $systemdata }}";
	  initHeatmap(THREE, systemdata);
	</script>
  </body>
</html>
