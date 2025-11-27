<!DOCTYPE html>
<html>
  <head>
	<title>Elite Dangerous Powerplay Tools</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
  </head>
  <body>
	<h1>Powerplay Tools</h1>

	<h2>Alternative Documentation Formats</h2>

	<p><a href="{{ route('power.refcard') }}">Reference Card for Powerplay activities</a></p>
	    
	<h2>System Support Report</h2>

	<x-powercontrolform />

	<h2>Current Powerplay Status</h2>
	@if($hipmode)
	    <p>Restricted to HIP 87621 fight (<a href="{{ route('power.index') }}">all systems</a>)</p>
	@else
	    <p>All systems (<a href="{{ route('power.hipindex') }}">HIP 87621</a>)</p>
	@endif
	<table>
	    <tr>
		<td>Status...</td>
		<th>Systems</th>
		<th>Control Points</th>
		<th>Balance</th>
	    </tr>
	    <tr>
		<th>Occupied</th>
		<td>{{ $occupied }}</td>
		<td>{{ number_format($totalcp) }}</td>
		<td>{{ number_format($reinforcement) }}R - {{ number_format($undermining) }}U ({{ $undermining > 0 ? number_format($reinforcement/$undermining, 1) : "-" }}x) - {{ number_format($decay) }}D</td>
	    </tr>
	    <tr>
		<th>Reinforced</th>
		<td>{{ $reinforced }}</td>
		<td>{{ number_format($reinforcedcp) }}</td>
		<td></td>
	    </tr>
	    <tr>
		<th>Acquisition</th>
		<td>{{ $acquirable }}</td>
		<td>{{ number_format($acquisition) }}</td>
		<td></td>
	    </tr>
	</table>
	<p>{{ $netreinforcement }} systems being net reinforced, {{ $netundermining }} systems being net undermined, ratio {{ $netundermining > 0 ? number_format($netreinforcement/$netundermining, 1) : "-" }}x</p>

	<h3>Per-Power Ratios</h3>

	<table>
	    <thead>
		<tr>
		    <th>Power</th><th>Systems</th><th>Reinforcement</th><th>Undermining</th><th>R:U Ratio</th><th>Decay</th><th>D:U Ratio</th><th>Real activity per system</th>
		</tr>
	    </thead>
	    <tbody>
		@foreach ($perpowerratios as $entry)
		    <tr>
			<td>{{ $entry->power }}</td>
			<td>{{ number_format($entry->c) }}</td>
			<td>{{ number_format($entry->r) }}</td>
			<td>{{ number_format($entry->u) }}</td>
			<td>
			    @if ($entry->u == 0)
				-
			    @else
				{{number_format($entry->r / $entry->u, 1)}}
			    @endif
			</td>
			<td>{{ number_format($entry->d) }}</td>
			<td>
			    @if ($entry->u == 0)
				-
			    @else
				{{number_format($entry->d / $entry->u, 1)}}
			    @endif
			</td>
			<td>{{ number_format(($entry->r + $entry->u) / $entry->c) }}</td>
		    </tr>
		@endforeach
	    </tbody>
	</table>
	
	<h3>Successfully Undermined Systems</h3>
	@if ($underminedlist->count() == 0)
	    <p>None so far this week.</p>
	@else
	<table>
	    <thead>
		<tr>
		    <th>Power</th><th>System</th><th>State</th><th>Margin</th><th>Last update</th>
		</tr>
	    </thead>
	    <tbody>
		@foreach ($underminedlist as $usys)
		    <tr>
			<td>{{ $usys->power }}</td>
			<td>{{ $usys->name }}</td>
			<td>{{ $usys->powerstate }}</td>
			<td>{{ number_format($usys->reinforcement) }} - {{ number_format($usys->undermining) }}</td>
			<td>{{ $usys->updated_at->format("j F H:i") }}
		    </tr>
		@endforeach
	    </tbody>
	</table>
	@endif
	
	<p>({{ $reinforcedlist->count() }} systems successfully reinforced.)</p>

	<h3>Heavily Undermined Systems</h3>

	@if ($maxundermined->count() == 0)
	    <p>None so far this week.</p>
	@else
	    <table>
		<thead>
		    <tr>
			<th>Power</th><th>System</th><th>State</th><th>Undermining</th><th>Reinforcement</th><th>Last update</th>
		    </tr>
		</thead>
		<tbody>
		    @foreach ($maxundermined as $usys)
			<tr>
			    <td>{{ $usys->power }}</td>
			    <td>{{ $usys->name }}</td>
			    <td>{{ $usys->powerstate }}</td>
			    <td>{{ number_format($usys->undermining) }}</td>
			    <td>{{ number_format($usys->reinforcement) }}</td>
			    <td>{{ $usys->updated_at->format("j F H:i") }}
			</tr>
		    @endforeach
		</tbody>
	    </table>
	@endif
	
	<p>({{ $overreinforced }} systems reinforced more than the greatest undermining.)</p>

	
	@if (!$hipmode)
	<h2>Overall Power Maps</h2>

	<p>Schematics of Power connectivity</p>

	<p><a href="{{ route('power.loose') }}">Check for loose systems</a> - these may indicate data errors - and <a href="{{ route('power.dataage') }}">check current data age</a>.</p>
	
	<table>
	    <thead>
		<tr>
		    <th>Week</th>
		    <th>Starting Date</th>
		    @foreach ($powers as $power => $home)
			<th>{{ $power }}</th>
		    @endforeach
		</tr>
	    </thead>
	    <tbody>
	    @for ($i=$week ; $i>=0 ; $i--)
		<tr>
		    <td>{{ $i }}</td>
		    <td>{{ Carbon\Carbon::parse("2024-10-24")->addWeeks($i)->format("j F Y") }}</td>
		    @foreach ($powers as $power => $home)
			<td>
			    @if ($i == 0 && $power == "Nakato Kaine")
				-
			    @else
			    <a href="{{ route('power.week', [$power, $i]) }}" title="{{ $power }} week {{ $i }}">
				Show
			    </a>
			    @endif
			</td>
		    @endforeach
		</tr>
	    @endfor
	    </tbody>
	</table>
	@endif 
  </body>
</html>
