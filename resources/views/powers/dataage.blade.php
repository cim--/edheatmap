<!DOCTYPE html>
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
	      <th scope="col" class="totalcell">Total</th>
	  </tr>
	  </thead>
	  <tfoot>
	      <tr>
		  <th scope="row" class="totalcell">TOTAL</th>
		  @foreach ($powers as $power => $home)
		      <td class="totalcell">{{ $ptotal[$power] ?? 0 }}</td>
		  @endforeach
		  <td class="totalcell">{{ $ptotal['null'] ?? 0 }}</td>
		  <td class="totalcell">{{ App\System::where('population', '>', 0)->count() }}</td>
	      </tr>
		 
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
		      <td class="totalcell">{{ $wtotal[$w] ?? 0 }}</td>
		  </tr>
	      @endfor
	  </tbody>
      </table>

      <p>Plus {{ $claims }} pending claims for a total of {{ $total }} systems.</p>

      <h2>Oldest systems, by Power</h2>

      <ul>
      @foreach ($oldest as $power => $systems)
	  <li><strong>{{ $power == "null" ? "None" : $power }}</strong>: {{ $systems->join("; ") }}</li>
      @endforeach
      </ul>

      
  </body>
</html>
