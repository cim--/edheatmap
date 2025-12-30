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
      <p>These use the "Control Points" shown on the tug-of-war display on the map. Personally-earned merits for rank are usually four times greater than these (but can have different multipliers).</p>
      <ul>
	  <li>To start Acquisition Conflict: 36,000 control points</li>
	  <li>To Acquire a system: 120,000 control points (including the above)</li>
	  <li>To reinforce a system to Fortified from minimum Exploited: 350,000 control points</li>
	  <li>To reinforce a system to Stronghold from minimum Fortified: 650,000 control points</li>
	  <li>To reinforce a system to maximum Stronghold from minimum Stronghold: 1,000,000 control points</li>
      </ul>


      
      <h2>Weekly Tasks</h2>

      <p>Each player is given five tasks at the start of the Powerplay cycle, granting a significant number of bonus personal merits - though no additional effect on the control score of the system they are completed in beyond those obtained from the task activities. Players starting the cycle at rank 40 are higher get an additional five tasks (total ten).</p>

      <p>Weekly tasks may specify particular systems to be carried out in; these will normally be near to the player's start of cycle location. To reduce the chance of being given an impossible task, it is best to start the week near a border with another Power.</p>

      <p>Weekly tasks are mostly optional and there is no penalty for not completing them - a new set will be given the following week regardless. The initial set of five tasks given when joining a Power is required to unlock rank progression (but will never specify systems, so should always be possible).</p>

      <h2>System Types</h2>
      
	<ul>
	    <li>Friendly System: any Exploited, Fortified or Stronghold of your Power</li>
	    <li>Unfriendly System: any Exploited, Fortified or Stronghold of any other Power</li>
	    <li>Target System: the system being affected</li>
	    <li>Supporting System: a Fortified system within 20 LY of an Acquisition System, or a Stronghold system within 30 LY</li>
	    <li>Reinforcement System: any friendly system <strong>except</strong> the capital/HQ system.</li>
	    <li>Undermining System: any unfriendly system <strong>except</strong> the capital/HQ systems.</li>
	    <li>Acquisition System: any neutral system within range of one of your Supporting Systems.</li>
	    <li>Unoccupied System: any neutral system, whether in range or not.</li>
	    <li>Acquisition Conflict System: a neutral system in which two or more Powers (including yours) have reached the Conflict threshold.</li>
	</ul>

	<h2>Activity Types</h2>
	
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
		<tr><td>Bounty Hunting</td><td>Yes</td><td>Yes</td><td>Only versus Delaine <strong>(+SC)</strong></td><td>High</td><td>No</td><td>Yes</td><td>Merit proportional to bounty claim, awarded on kill</td><td>In Target System</td><td>n/a</td><td>Cashing in the bounties at a friendly system power contact afterwards gives a bonus</td><td>Positive, generally for system controller</td></tr>
		<tr class="disabled"><td>Collect Escape Pods <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>No</td><td>Yes</td><td>Power Signal Sources are a good place to look</td><td>In Target System</td><td>Supporting System Power Contact</td><td>Certain megaships and other POIs can give a higher rate of escape pods. Unavailable at Anarchy stations.</td><td>Mildly positive for hand-in station owner</td></tr>
		<tr><td>Holoscreen Hacking</td><td>Yes</td><td>Yes</td><td>Yes <strong>(+SC)</strong></td><td>No</td><td>No</td><td>Reinforcement Only (Fine elsewhere)</td><td>Requires Recon Limpet</td><td>Orbital Starports</td><td>n/a</td><td>In Acquisition and Undermining, rapidly damages reputation with station owner. In Reinforcement may not be available away from front lines.</td><td>None</td></tr>
		<tr><td>Power Kills</td><td>Yes</td><td>Yes</td><td>Yes <strong>(+SC)</strong></td><td>No</td><td>Some</td><td>Reinforcement Only</td><td>In Undermining systems, killing ships or soldiers belonging to another Undermining Power does nothing</td><td>In Target System</td><td>n/a</td><td>In Acquisition and Undermining, kills are illegal but do not increase notoriety</td><td>None</td></tr>
		<tr><td>Retrieve Power Goods <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Goods are in locked containers, ebreach or combination to open</td><td>Surface settlements in target system</td><td>Supporting System Power Contact</td><td></td><td>None</td></tr>
		<tr><td>Scan Datalinks</td><td>Yes</td><td>Yes</td><td>Yes (-SC)</td><td>No</td><td>No</td><td>Yes</td><td>Scan “Ship Log Uplink” with Data Link Scanner</td><td>Non-dockable megaships</td><td>n/a</td><td>Only once per megaship per fortnight, requires ship log uplink to contain a message.</td><td>None (unless combined with a mission to scan the same ship)</td></tr>
		<tr><td>Sell for large profits <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>Moderate</td><td>No</td><td>Yes</td><td>Any cargo worth 40% or more profit</td><td>Station in supporting system</td><td>Station in target system</td><td>Once profit threshold met, more expensive goods are better. Undocumented location requirement.</td><td>Positive for station owner</td></tr>
		<tr><td>Sell for large profits <abbr title="Reinforcement">R</abbr></td><td>No</td><td>Yes</td><td>No</td><td>Moderate</td><td>No</td><td>Yes</td><td>Any cargo worth 40% or more profit</td><td>Station in any system</td><td>Station in target system</td><td>Once profit threshold met, more expensive goods are better</td><td>Positive for station owner</td></tr>
		<tr><td>Sell mined resources <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Sell any actually mined goods, note location requirements</td><td>Mining sites in Supporting System</td><td>Station in target system</td><td>Location requirement is unusually harsh, and not documented</td><td>Positive for station owner</td></tr>
		<tr><td>Sell rare goods</td><td>Yes</td><td>Yes</td><td>No</td><td>Low</td><td>No</td><td>Usually (Fine only)</td><td>Sell rare goods</td><td>Any rare goods producer outside target system</td><td>Station in target system</td><td>Rares must be legal in target system. Must not be transported by Fleet Carrier.</td><td>Positive for station owner</td></tr>
		<tr><td>Transfer Power Data <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Downloadable from data ports</td><td>Odyssey settlements</td><td>Supporting System Power Contact</td><td>Each Power has preferred types of data which give better merits. Data type chances related to data port type.</td><td>None</td></tr>
		<tr><td>Transport Power Commodity <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>No</td><td>Yes, but other Power agents may attack</td><td>Location is crucial</td><td>Power Contact in Supporting System</td><td>Power Contact in Target System</td><td>Limited allocation per half hour, 15-250 dependent on rank. Cargo disappears at end of cycle if not delivered.</td><td>None</td></tr>
		<tr><td>Upload Power Malware <abbr title="Acquisition">A</abbr></td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>Yes</td><td>If not caught</td><td>Upload Power Injection Malware to data ports</td><td>Any Power contact</td><td>Odyssey settlements in target system</td><td>Only one item can be uploaded per port. Long upload time.</td><td>None</td></tr>
		<tr><td>Complete Support Missions</td><td>Conflict only</td><td>Yes</td><td>Yes (-SC)</td><td>Moderate</td><td>No</td><td>Yes</td><td>Any ship mission in the “Support” category</td><td>Station in target system</td><td>n/a</td><td>Merit gain proportional to donation size for those missions, with cargo donations much more effective.</td><td>Positive for mission faction</td></tr>
		<tr><td>Complete Restore/Reactivate Missions</td><td>Yes</td><td>Yes</td><td>No</td><td>Moderate</td><td>Yes</td><td>Yes</td><td>Any Odyssey mission in the “Support” category</td><td>Station in target system</td><td>via Odyssey base in target system</td><td>Static merit value regardless of reward choice.</td><td>Positive for mission faction</td></tr>
		<tr><td>Flood markets with low value goods A</td><td>Conflict only</td><td>No</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Goods must sell for 500 cr or less, and be on the station market as supply or demand</td><td>Station in Supporting system</td><td>Station in target system</td><td>The cheaper the better. Hydrogen Fuel is usually a safe bet, Limpets also work well.</td><td>Variable but small for station owner</td></tr>
		<tr><td>Flood markets with low value goods U</td><td>No</td><td>No</td><td>Yes (-SC)</td><td>Low</td><td>No</td><td>Yes</td><td>Goods must sell for 500 cr or less, and be on the station market as supply or demand</td><td>Station in any system</td><td>Station in target system</td><td>The cheaper the better. Hydrogen Fuel is usually a safe bet, Limpets also work well.</td><td>Variable but small for station owner</td></tr>
		<tr><td>Scan ships and wakes</td><td>Conflict only</td><td>Yes</td><td>No</td><td>High</td><td>No</td><td>Yes</td><td>Normal scan of ships.</td><td>In Target System</td><td>n/a</td><td>Autoscans count (including your own SLF, though the merit count is far too small to be exploitable)</td><td>None</td></tr>
		<tr class="disabled"><td>Collect Escape Pods <abbr title="Reinforcement">R</abbr></td><td>No</td><td>Yes</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Damaged or Occupied Escape Pods</td><td>In Target System</td><td>Target System Power Contact</td><td> Unavailable at Anarchy stations. S&R payout bonuses help with this</td><td>None</td></tr>
		<tr><td>Exobiology</td><td>No</td><td>Yes</td><td>No</td><td>High</td><td>Yes</td><td>Yes</td><td></td><td>Anywhere</td><td>Station in target system</td><td>Data collected after 7 Nov 3310 only</td><td>None</td></tr>
		<tr><td>Exploration Data</td><td>No</td><td>Yes</td><td>No</td><td>High</td><td>No</td><td>Yes</td><td>Merits per system, not per page</td><td>Anywhere >20LY</td><td>Station in target system</td><td>Data collected after 7 Nov 3310 only</td><td>Positive for station owner</td></tr>
		<tr><td>Collect Salvage <abbr title="Reinforcement">R</abbr></td><td>No</td><td>Yes</td><td>No</td><td>Low</td><td>No</td><td>Yes</td><td>Black boxes, Personal Effects, Wreckage Components</td><td>In Target System</td><td>Power Contact in Target System</td><td>Unavailable at Anarchy stations.</td><td>Positive for station owner</td></tr>
		<tr><td>Sell mined resources <abbr title="Reinforcement">R</abbr><abbr title="Undermining">U</abbr></td><td>No</td><td>Yes</td><td>Yes</td><td>Moderate</td><td>No</td><td>Yes</td><td>Sell any actually mined goods, note location requirements</td><td>Mining sites in Target System</td><td>Station in target system</td><td>Location requirement is unusually harsh, and not documented. Merits proportional to sale price.</td><td>Positive for station owner</td></tr>
		<tr><td>Transport Power Commodity <abbr title="Reinforcement">R</abbr></td><td>No</td><td>Yes</td><td>No</td><td>No</td><td>No</td><td>Yes, but other Power agents may attack</td><td></td><td>Power Contact in Stronghold System</td><td>Power Contact in Target System</td><td>Limited allocation per half hour, 15-250 dependent on rank. Can’t reinforce a system with its own supplies. Cargo disappears at end of cycle if not delivered.</td><td>None</td></tr>
		<tr class="disabled"><td>Collect Escape Pods <abbr title="Undermining">U</abbr></td><td>No</td><td>No</td><td>Yes (-SC?)</td><td>No</td><td>No</td><td>Yes</td><td>Power Signal Sources are a good place to look</td><td>In Target System</td><td>Friendly System Power Contact</td><td> Unavailable at Anarchy stations. Certain megaships and other POIs can give a higher rate of escape pods</td><td>Mildly positive for hand-in station owner</td></tr>
		<tr><td>Commit Crimes</td><td>No</td><td>No</td><td>Yes (-SC)</td><td>Very Low</td><td>No</td><td>No</td><td>Murder of Power or minor faction ships or personnel</td><td>In Target System</td><td>n/a</td><td>System authority appear not to count. Bounty value applied before notoriety multipliers.</td><td>Negative for ship owner (irrelevant for Power ships)</td></tr>
		<tr><td>Collect Salvage <abbr title="Undermining">U</abbr></td><td>No</td><td>No</td><td>Yes (-SC)</td><td>Low</td><td>No</td><td>Yes</td><td>Black boxes, Personal Effects, Wreckage Components</td><td>In Target System</td><td>Friendly System Power Contact</td><td>Unavailable at Anarchy stations.</td><td>Positive for station owner</td></tr>
		<tr><td>Transfer Power Data <abbr title="Undermining">U</abbr></td><td>No</td><td>No</td><td>Yes (-SC)</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Downloadable from data ports</td><td>Odyssey settlements</td><td>Friendly System Power Contact</td><td>Each Power has preferred types of data which give better merits. Data types chances related to data port type.</td><td>None</td></tr>
		<tr><td>Transfer Power Data <abbr title="Reinforcement">R</abbr></td><td>No</td><td>Some Types</td><td>No</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Downloadable from data ports</td><td>Odyssey settlements</td><td>Same System Power Contact</td><td>Research and Industrial data do not work in Reinforcement</td><td>None</td></tr>
		<tr><td>Retrieve Power Goods <abbr title="Undermining">U</abbr></td><td>No</td><td>No</td><td>Yes (-SC)</td><td>Low</td><td>Yes</td><td>If not caught</td><td>Goods are in locked containers, ebreach or combination to open</td><td>Surface settlements in target system</td><td>Friendly System Power Contact</td><td></td><td>None</td></tr>
		<tr><td>Transport Power Commodity <abbr title="Undermining">U</abbr></td><td>No</td><td>No</td><td>Yes (-SC)</td><td>No</td><td>No</td><td>Yes, but other Power agents can legally attack</td><td></td><td>Power Contact in Stronghold System</td><td>Power Contact in Target System</td><td>Limited allocation per half hour, 15-250 dependent on rank. Cargo disappears at end of cycle if not delivered.</td><td>None</td></tr>
		<tr><td>Upload Power Malware <abbr title="Undermining">U</abbr></td><td>No</td><td>No</td><td>Yes (-SC)</td><td>No</td><td>Yes</td><td>If not caught</td><td>Upload Power Tracker Malware to data ports</td><td>Any Power contact</td><td>Odyssey settlements in target system</td><td>Only one item can be uploaded per port. Long upload time.</td><td>None</td></tr>
	    </tbody>
	</table>
	<p>A, R and U on the end of an activity description are used to distinguish the same base activity carried out in different states, as some of the details for successful completion may vary.</p>
	<h3>Stronghold Carriers</h3>

	<p>As noted below, most Undermining methods do not work at all in systems containing a Stronghold Carrier. This is noted with a "-SC" (doesn't work) or "<strong>+SC</strong>" (works) entry in the Undermining column.</p>

	<h2 id="bonuses">Merit and Control Point bonuses and penalties.</h2>
	
	<p>There are a very large number of bonuses and penalties to both Control Points and Merits, some of which are at least somewhat documented. All of them stack together.</p>

	<p>Some of these modifiers are currently only in operation in the HIP 87621 enclave.</p>

	<table>
	    <thead>
		<tr>
		    <th>Name</th><th>Documented?</th><th>Affects</th><th>System Type</th><th>Direction</th><th>Magnitude</th><th>Description</th><th>HIP enclave only</th>
		</tr>
	    </thead>
	    <tbody>
		<tr><td>Ethos Bonus</td><td>Yes</td><td>CPs and Merits</td><td>All</td><td>Bonus</td><td>+50%</td><td>Benefits doing the preferred activities of your Power. Each Power and each Activity has a different list of these.</td><td>No</td></tr>
		<tr><td>System Strength Penalty</td><td>Minimally</td><td>CPs and Merits</td><td>Undermining</td><td>Penalty</td><td>variable by system, up to -50%?</td><td>Makes system more resilient, descriptor likely covering a wider range stated on map.</td><td>No</td></tr>
		<tr><td>Beyond Frontline Penalty</td><td>Minimally</td><td>CPs and Merits</td><td>Undermining</td><td>Penalty</td><td>variable by system, up to -50%?</td><td>For systems far from your own territory, worsens with distance, descriptor likely covering a wider range stated on map.</td><td>No</td></tr>
		<tr><td>System Rank Penalty</td><td>No</td><td>CPs and Merits</td><td>Undermining</td><td>Penalty</td><td>Very uncertain (could be around -30%?)</td><td>Fortified and Stronghold systems are harder to Undermine than the same system in Exploited form. Unclear if this is a multiplier to System Strength Penalty or an entirely separate modifier.</td><td>No</td></tr>
		<tr><td>Stronghold Carrier Nullification</td><td>No</td><td>CPs and Merits</td><td>Undermining</td><td>Penalty</td><td>-100%!</td><td>Most Undermining methods do not work at all if a Stronghold Carrier is present (see below).</td><td>No</td></tr>
		<tr><td>General Undermining Bonus</td><td>Yes</td><td>Merits only</td><td>Undermining</td><td>Bonus</td><td>+15%</td><td>Granted for Undermining any system</td><td>No</td></tr>
		<tr><td>Focused Undermining Bonus</td><td>Inconsistently</td><td>Merits only</td><td>Undermining</td><td>Bonus</td><td>+25%</td><td>Granted for Undermining daily target systems (blue crosshair icon). Always stacked with the General Bonus for +40% total</td><td>Yes</td></tr>
		<tr><td>General Reinforcement Penalty</td><td>Yes</td><td>Merits only</td><td>Reinforcement</td><td>Penalty</td><td>-35%</td><td>Granted for Reinforcing any system</td><td>No</td></tr>
		<tr><td>Resistance Reinforcement Modifier</td><td>Only in patch notes</td><td>Merits only</td><td>Reinforcement</td><td>Varies</td><td>Varies from -20% to +30%</td><td>Further supplement/cancellation of General Reinforcement Penalty depending on recent enemy Undermining in the system.</td><td>No</td></tr>
		<tr><td>Overkill Reinforcement Penalty</td><td>No</td><td>Merits only</td><td>Reinforcement</td><td>Penalty</td><td>-20%</td><td>Further penalty for Reinforcing a system already above the next threshold (or a maxed Stronghold). (so -75% stacked with the General and Resistance modifiers)</td><td>No</td></tr>
		<tr><td>Emergency Defence Bonus</td><td>Forum post only</td><td>Merits only</td><td>Reinforcement</td><td>Bonus</td><td>+35%</td><td>Cancels the General Reinforcement Penalty in systems which are net Undermined to the next state down (red ! icon). As these will often have a Resistance Modifier too the net effect is likely to be a bonus</td><td>Yes</td></tr>
		<tr><td>Acquisition Follow-through Bonus</td><td>Patch notes only</td><td>Merits Only</td><td>Acquisition</td><td>Bonus</td><td>5000-20000 merits</td><td>Personal merits awarded at the end of the week for significant contributions to Acquiring a system lost by another Power in the previous week due at least in part to actions by this Power.</td><td>Yes</td></tr>
		<tr><td>Assignment Completion Bonus</td><td>Yes</td><td>Merits Only</td><td>All</td><td>Bonus</td><td>2000-4000 merits</td><td>Personal merits granted in addition to the intrinsic value of the task for completing weekly assignments</td><td>No</td></tr>
		<tr><td>Exploration Data Exchange Rate</td><td>Patch notes only</td><td>Merits Only</td><td>Reinforcement</td><td>Bonus</td><td>+50%</td><td>Exploration data pays out at a baseline of 6 merits/CP rather than the typical 4</td><td>No</td></tr>
	    </tbody>
	</table>

	<h3>Examples</h3>
	
	<p>So, for example, carrying out a ship scan (3 control points baseline value) as a Kaine pledge in a Reinforcement system in the HIP enclave that has recently been Undermined below the threshold will score as follows:</p>
	<ul>
	    <li>Control point modifier: ethos bonus at +50%</li>
	    <li>Control points earned: 4.5, rounded up to 5.</li>
	    <li>Merits modifier: -35% for reinforcement</li>
	    <li>Merits modifier: +35% for emergency defence</li>
	    <li>Merits modifier: +30% for system having had recent heavy undermining</li>
	    <li>Standard merits multiplier: 4x</li>
	    <li>Total merits multiplier: 5.2x, so 26 Merits earned.</li>
	</ul>

	<p>Or alternatively, as an Antal pledge carrying out a 1 million credit donation mission (baseline 27 control points) to an enemy Fortified system.</p>

	<ul>
	    <li>Control point modifiers: System Strength Penalty, System Rank Penalty and Beyond Frontline Penalty apply. Total effect highly system specific. Assuming a total of -50% for this example.</li>
	    <li>Control points earned: 14</li>
	    <li>Merits modifier: +15% for standard undermining</li>
	    <li>Standard merits multiplier: 4x</li>
	    <li>Total merits multiplier: 4.6x, so 64 Merits earned.</li>
	</ul>

	<p>In general, Reinforcement gets merit (but not control point) penalties, while Undermining usually gets control point penalties which are often substantially larger than its merit bonuses. Acquisition is a neutral "baseline".</p>

	
	<script type="text/javascript">
	 var table = new DataTable('#refcard');
	</script>
  </body>
</html>
