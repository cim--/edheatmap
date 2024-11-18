<!DOCTYPE html>
<html>
  <head>
	<title>Elite Dangerous Powerplay Tools: Reference Card</title>
	<link rel='stylesheet' href='/css/heatmap.css' type='text/css'>
	<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" type="text/css">
	<script type="text/javascript" src="/js/heatmap.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
	
  </head>
  <body>
      <p><a href="{{ route('power.index') }}">Tool index</a></p>

      <h1>Powerplay Tools: Reference Card</h1>

      <h2>Activity Requirements</h2>
      <ul>
	  <li>To start Acquisition Conflict: 35,000 merits</li>
	  <li>To Acquire a system: 120,000 merits (including the 35,000 above)</li>
	  <li>To reinforce a system to Fortified from minimum Exploited: 333,000 merits</li>
	  <li>To reinforce a system to Stronghold from minimum Fortified: 667,000 merits</li>
	  <li>To reinforce a system to maximum Stronghold from minimum Stronghold: 1,000,000 merits</li>
      </ul>

      <h2>Activity Types</h2>
      
	<ul>
	    <li>Friendly System: any Exploited, Fortified or Stronghold of your Power</li>
	    <li>Target System: the system being affected</li>
	    <li>Supporting System: a Fortified system within 20 LY of an Acquisition System, or a Stronghold system within 30 LY</li>
	</ul>
	
	<table id="refcard" data-paging="false">
	    <thead>
		<tr>
		    <th>Activity</th>
		    <th title="Available in Acquisition systems">Acquisition?</th>
		    <th title="Available in Reinforcement systems">Reinforcement?</th>
		    <th title="Available in Undermining systems">Undermining?</th>
		    <th title="Ease of doing accidentally while in the system anyway">Passive?</th>
		    <th title="Requires Odyssey expansion">Odyssey?</th>
		    <th title="Generates minor faction bounties">Legal?</th>
		    <th>Details</th>
		    <th>Pickup/Activity Location</th>
		    <th>Hand-in Location</th>
		    <th>Notes</th>
		    <th>BGS effect?</th>
		</tr>
	    </thead>
	    <tbody>
		<tr><td>Bounty Hunting</td><td>Yes</td><td>Yes</td><td>No</td><td>High</td><td>No</td><td>Yes</td><td>Merit proportional to bounty claim, awarded on kill</td><td>In Target System</td><td>n/a</td><td>Cashing in the bounties at a friendly system power contact afterwards gives a bonus</td><td>Positive, generally for system controller</td></tr>
		<tr><td>Collect Escape Pods A</td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>No</td><td>Yes</td><td>Power Signal Sources are a good place to look</td><td>In Target System</td><td>Supporting System Power Contact</td><td>Certain megaships and other POIs can give a higher rate of escape pods</td><td>Mildly positive for hand-in station owner</td></tr>
		<tr><td>Holoscreen Hacking</td><td>Yes</td><td>Yes</td><td>Yes</td><td>No</td><td>No</td><td>Reinforcement Only (Fine elsewhere)</td><td>Requires Recon Limpet</td><td>Starports</td><td>n/a</td><td>In Acquisition and Undermining, rapidly damages reputation with station owner</td><td>None</td></tr>
		<tr><td>Power Kills</td><td>Yes</td><td>Yes</td><td>Yes</td><td>No</td><td>No</td><td>Reinforcement Only</td><td>In Undermining systems, killing ships belonging to another Undermining Power does nothing</td><td>In Target System</td><td>n/a</td><td>In Acquisition and Undermining, kills are illegal but do not increase notoriety</td><td>None</td></tr>
		<tr><td>Retrieve Power Goods A</td><td>Yes</td><td>No</td><td>No</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Goods are in locked containers, ebreach or combination to open</td><td>Surface settlements in target system</td><td>Supporting System Power Contact</td><td></td><td>None</td></tr>
		<tr><td>Scan Datalinks</td><td>Yes</td><td>Yes</td><td>Yes</td><td>No</td><td>Yes</td><td>Yes</td><td>Scan “Ship Log Uplink” with Data Link Scanner</td><td>Non-dockable megaships</td><td>n/a</td><td>Only once per megaship per fortnight</td><td>None (unless combined with a mission to scan the same ship)</td></tr>
		<tr><td>Sell for large profits A</td><td>Yes</td><td>No</td><td>No</td><td>Moderate</td><td>No</td><td>Yes</td><td>Any cargo worth 40% or more profit</td><td>Station in supporting system</td><td>Station in target system</td><td>Once profit threshold met, more expensive goods are better. Undocumented location requirement.</td><td>Positive for station owner</td></tr>
		<tr><td>Sell for large profits R</td><td>No</td><td>Yes</td><td>No</td><td>Moderate</td><td>No</td><td>Yes</td><td>Any cargo worth 40% or more profit</td><td>Station in any system</td><td>Station in target system</td><td>Once profit threshold met, more expensive goods are better</td><td>Positive for station owner</td></tr>
		<tr><td>Sell mined resources A</td><td>Yes</td><td>No</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Sell any actually mined goods, note location requirements</td><td>Mining sites in Supporting System</td><td>Station in target system</td><td>Location requirement is unusually harsh, and not documented</td><td>Positive for station owner</td></tr>
		<tr><td>Sell rare goods</td><td>Yes</td><td>Yes</td><td>No</td><td>Low</td><td>No</td><td>Usually (Fine only)</td><td>Sell rare goods</td><td>Any rare goods producer outside target system</td><td>Station in target system</td><td>Merit gain is better for higher sale prices</td><td>Positive for station owner</td></tr>
		<tr><td>Transfer Power Data A</td><td>Yes</td><td>No</td><td>No</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Downloadable from data ports</td><td>Odyssey settlements</td><td>Supporting System Power Contact</td><td>Each Power has preferred types of data which give better merits. Data types may be related to data port type.</td><td>None</td></tr>
		<tr><td>Transport Power Commodity A</td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>No</td><td>Yes, but other Power agents may attack</td><td>Location is crucial</td><td>Power Contact in Supporting System</td><td>Power Contact in Target System</td><td>Limited allocation per half hour, somewhat dependent on rank</td><td>None</td></tr>
		<tr><td>Upload Power Malware A</td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>No</td><td>If not caught</td><td>Upload to data ports</td><td>Any Power contact</td><td>Odyssey settlements in target system</td><td>Only one item can be uploaded per port. Long upload time.</td><td>None</td></tr>
		<tr><td>Complete Support Missions</td><td>Conflict only</td><td>Yes</td><td>Yes</td><td>Moderate</td><td>Some</td><td>Yes</td><td>Any mission in the “Support” category</td><td>Station in target system</td><td>n/a</td><td>Merit gain proportional to donation size for those missions. Restore/reactivate Odyssey missions are very good.</td><td>Positive for mission faction</td></tr>
		<tr><td>Flood markets with low value goods</td><td>Conflict only</td><td>No</td><td>Yes</td><td>Low</td><td>No</td><td>Yes</td><td>Goods must sell for 500 cr or less, and be on the station market as supply or demand</td><td>Station in any system</td><td>Station in target system</td><td>The cheaper the better. Hydrogen Fuel is usually a safe bet.</td><td>Variable but small for station owner</td></tr>
		<tr><td>Scan ships and wakes</td><td>Conflict only</td><td>Yes</td><td>No</td><td>High</td><td>No</td><td>Yes</td><td>Normal scan of ships.</td><td>In Target System</td><td>n/a</td><td>Autoscans count (including your own SLF, though the merit count is far too small to be exploitable)</td><td>None</td></tr>
		<tr><td>Collect Escape Pods R</td><td>No</td><td>Yes</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Damaged or Occupied Escape Pods</td><td>In Target System</td><td>Target System Power Contact</td><td>S&R payout bonuses help with this</td><td>None</td></tr>
		<tr><td>Exobiology</td><td>No</td><td>Yes</td><td>No</td><td>High</td><td>Yes</td><td>Yes</td><td></td><td>Anywhere</td><td>Station in target system</td><td>Data collected after 7 Nov 3310 only</td><td>None</td></tr>
		<tr><td>Exploration Data</td><td>No</td><td>Yes</td><td>No</td><td>High</td><td>No</td><td>Yes</td><td>Merits per system, not per page – cheap systems get nothing</td><td>Anywhere >20LY</td><td>Station in target system</td><td>Data collected after 7 Nov 3310 only</td><td>Positive for station owner</td></tr>
		<tr><td>Collect Salvage R</td><td>No</td><td>Yes</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Black boxes, Personal Effects, Wreckage Components</td><td>In Target System</td><td>Power Contact in Target System</td><td></td><td>Positive for station owner</td></tr>
		<tr><td>Sell mined resources RU</td><td>No</td><td>Yes</td><td>Yes</td><td>Moderate</td><td>No</td><td>Yes</td><td>Sell any actually mined goods, note location requirements</td><td>Mining sites in Target System</td><td>Station in target system</td><td>Location requirement is unusually harsh, and not documented. Merits proportional to sale price.</td><td>Positive for station owner</td></tr>
		<tr><td>Transport Power Commodity R</td><td>No</td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>Yes, but other Power agents may attack</td><td></td><td>Power Contact in Stronghold System</td><td>Power Contact in Target System</td><td>Can’t reinforce a system with its own supplies</td><td>None</td></tr>
		<tr><td>Collect Escape Pods U</td><td>No</td><td>No</td><td>Yes</td><td>No</td><td>No</td><td>Yes</td><td>Power Signal Sources are a good place to look</td><td>In Target System</td><td>Friendly System Power Contact</td><td>Certain megaships and other POIs can give a higher rate of escape pods</td><td>Mildly positive for hand-in station owner</td></tr>
		<tr><td>Commit Crimes</td><td>No</td><td>No</td><td>Yes</td><td>Very Low</td><td>No</td><td>No</td><td>Murder of Power or minor faction ships or personnel</td><td>In Target System</td><td>n/a</td><td>System authority appear not to count</td><td>Negative for ship owner (irrelevant for Power ships)</td></tr>
		<tr><td>Collect Salvage U</td><td>No</td><td>No</td><td>Yes</td><td>Low</td><td>No</td><td>Yes</td><td>Black boxes, Personal Effects, Wreckage Components</td><td>In Target System</td><td>Friendly System Power Contact</td><td></td><td>Positive for station owner</td></tr>
		<tr><td>Transfer Power Data U</td><td>No</td><td>No</td><td>Yes</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Downloadable from data ports</td><td>Odyssey settlements</td><td>Friendly System Power Contact</td><td>Each Power has preferred types of data which give better merits. Data types may be related to data port type.</td><td>None</td></tr>
		<tr><td>Retrieve Power Goods U</td><td>No</td><td>No</td><td>Yes</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Goods are in locked containers, ebreach or combination to open</td><td>Surface settlements in target system</td><td>Friendly System Power Contact</td><td></td><td>None</td></tr>
		<tr><td>Transport Power Commodity U</td><td>No</td><td>No</td><td>Yes</td><td>No</td><td>No</td><td>Yes, but other Power agents can legally attack</td><td></td><td>Power Contact in Stronghold System</td><td>Power Contact in Target System</td><td></td><td>None</td></tr>
		<tr><td>Upload Power Malware U</td><td>No</td><td>No</td><td>Yes</td><td>No</td><td>No</td><td>If not caught</td><td>Upload to data ports</td><td>Any Power contact</td><td>Odyssey settlements in target system</td><td>Only one item can be uploaded per port. Long upload time.</td><td>None</td></tr>
	    </tbody>
	</table>

	<script type="text/javascript">
	 var table = new DataTable('#refcard');
	</script>
  </body>
</html>
