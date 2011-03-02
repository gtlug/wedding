<?php
$this->headTitle('Stats');

$guestsCount = $this->guests->count();
$adhocCount = 0;
$foodSelectionCounts = array();
$remainders = array();
$attending = 0;

foreach($this->guests as $guest)
{
	if(!isset($foodSelectionCounts[$guest->foodId]))
	{
		$foodSelectionCounts[$guest->foodId] = 0;
	}
	
	if($guest->inviteId)
	{
		if(!isset($remainders[$guest->inviteId]))
		{
			$remainders[$guest->inviteId] = $guest->guests;
		}
		
		if($guest->attending)
		{
			$remainders[$guest->inviteId]--;
		}
	}
	else
	{
		$adhocCount++;
	}
	
	if($guest->attending)
	{
		$attending++;
		$foodSelectionCounts[$guest->foodId]++;
	}

}
$declines = array_sum($remainders);
?>
<dl>
	<dt>Total Guest Response Count</dt>
	<dd><?= $guestsCount ?></dd>

	<dt>Attending</dt>
	<dd><?= $attending ?></dd>
	
	<dt>Declines</dt>
	<dd><?= $declines ?></dd>	

	<dt>Ad-Hoc Count</dt>
	<dd><?= $adhocCount ?></dd>
	
	<dt>Food Choices</dt>
	<dd>
		<dl>
<?php foreach($this->foods as $food) { $foodId = $food->foodId; ?>
			<dt><?= $food->foodName ?></dt>
			<dd><?= isset($foodSelectionCounts[$foodId]) ? $foodSelectionCounts[$foodId] : 0 ?></dd>
			
<?php } /*foreach(foods)*/?>
		</dl>
	</dd>
</dl>
<?php if($this->showGuests) { ?>

<table>
<thead>
	<tr>
		<th>guestId</th>
		<th>inviteId</th>
		<th>guestName</th>
		<th>attending</th>
		<th>foodId</th>
		<th>dateUpdated</th>
	</tr>
</thead>
<tbody>
<?php foreach($this->guests as $guest) { ?>
	<tr>
		<td><?= $guest->guestId ?></td>
		<td><?= $guest->inviteId ?></td>
		<td><?= $guest->guestName ?></td>
		<td><?= $guest->attending ?></td>
		<td><?= $guest->foodId ?></td>
		<td><?= $guest->dateUpdated ?></td>
	</tr>
<?php } /*foreach(guests) {*/ ?>
</tbody>
</table>

<?php } else { /*if(showGuests)*/ ?>
<form action="" method="post">
<input type="password" class="password" name="password" />
<button type="submit" class="submit">!!!</button>
</form>
<?php } /*if(showGuests)...else*/ ?>