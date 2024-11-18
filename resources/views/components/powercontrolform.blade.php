
<p>Enter the name of a Fortified or Stronghold system to see the Exploited systems it supports, or an Exploited or Acquisition system to see its supporters.</p>

<form action="{{ route('power.control') }}" method="POST">
    @csrf
    <input type="text" name="system"><input type="Submit" value="Get Support Report">
</form>
