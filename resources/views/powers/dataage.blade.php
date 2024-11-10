<html>
  <head>
	<title>Elite Dangerous Powerplay Tools: Data age</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>

      <h1>Data Age Report</h1>

      <p>This reports on the general age of the data, for a rough indication of current quality.</p>

      <table>
	  <thead>
	  <tr>
	      <th scope="col">Week</th>
	      @foreach ($powers as $power => $home)
		  <th scope="col">{{ $power }}</th>
	      @endforeach
	      <th scope="col">No power</th>
	  </tr>
	  </thead>
	  <tbody>
	      @for ($w=$week;$w>=$min;$w--)
		  <tr>
		      <th scope="row">{{ $w }}
			  @if ($w == 1)
			      or earlier
			  @endif
		      </th>
		      @foreach ($powers as $power => $home)
			  <td>{{ $table[$w][$power] ?? '-' }}</td>
		      @endforeach
		      <td>{{ $table[$w]["null"] ?? '-' }}</td>
		  </tr>
	      @endfor
	  </tbody>
      </table>


      
  </body>
</html>
