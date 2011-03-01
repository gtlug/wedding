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