<?php
$this->headTitle('Stats');

$guestsCount = $this->guests->count();
$projectedCount = 0;
$adhocCount = 0;
$foodSelectionCounts = array();
$remainders = array();
$attending = 0;
$rowspans = array();

foreach($this->guests as $guest)
{
	if(!isset($foodSelectionCounts[$guest->foodId]))
	{
		$foodSelectionCounts[$guest->foodId] = 0;
	}
	
	if($guest->inviteId)
	{
		if(!isset($rowspans[$guest->inviteId]))
		{
			$rowspans[$guest->inviteId] = 0;
		}
		$rowspans[$guest->inviteId]++;
		
		if(!isset($remainders[$guest->inviteId]))
		{
			$remainders[$guest->inviteId] = $guest->guests;
			$projectedCount += $guest->guests;
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
$inviteCount = count($remainders);
$declines = array_sum($remainders);
?>
<dl>
	<dt>Invite Response Count</dt>
	<dd><?= $inviteCount ?></dd>

	<dt>Total Named Guest Response Count</dt>
	<dd><?= $guestsCount ?></dd>
	
	<dt>Total Projected Guest Response Count</dt>
	<dd><?= $projectedCount ?></dd>
	
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
			<dt>(<?= $foodId ?>) <?= $food->foodName ?></dt>
			<dd><?= isset($foodSelectionCounts[$foodId]) ? $foodSelectionCounts[$foodId] : 0 ?></dd>
			
<?php } /*foreach(foods)*/?>
		</dl>
	</dd>
</dl>
<?php if($this->showGuests) { ?>

<table border="1" cellpadding="5" cellspacing="0">
<thead>
	<tr>
		<th>dateUpdated</th>
		<th>guestName</th>
		<th>attending</th>
		<th>foodId</th>
		<th>guestId</th>
		<th>inviteId</th>
		<th>expected</th>
	</tr>
</thead>
<tbody>
<?php foreach($this->guests as $guest) { ?>
	<tr>
		<td><?= $guest->dateUpdated ?></td>
		<td><?= $guest->guestName ?></td>
		<td><?= $guest->attending ? 'Yes' : 'No' ?></td>
		<td><?= $guest->foodId ?></td>
		<td><?= $guest->guestId ?></td>
<?php if($guest->inviteId && isset($rowspans[$guest->inviteId])) { 
	if(false !== $rowspans[$guest->inviteId]) { ?>
		<td rowspan="<?= $rowspans[$guest->inviteId] ?>"><?= $guest->inviteId ?></td>
		<td rowspan="<?= $rowspans[$guest->inviteId] ?>"><?= $guest->guests ?></td>
<?php
 		$rowspans[$guest->inviteId] = false;
	} /*if(null !== $rowspans[$guest->inviteId]))*/		
} else { /*if(isset($rowspans[$guest->inviteId])) {*/ ?>		
		<td><?= $guest->inviteId ?></td>
		<td><?= $guest->guests ?></td>
<?php } /*if(isset($rowspans[$guest->inviteId]))...else {*/ ?>
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