<?php
$this->headTitle('Stats');

$guestsCount = $this->guests->count();
$rogueCount = 0;
$foodSelectionCounts = array();
$remainders = array();
$attending = 0;

foreach($this->guests as $guest)
{
	if(!isset($foodSelectionCounts[$guest->foodId]))
	{
		$foodSelectionCounts[$guest->foodId] = 0;
	}
	$foodSelectionCounts[$guest->foodId]++;
	
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
		$rogueCount++;
	}
	
	if($guest->attending)
	{
		$attending++;
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

	<dt>Rogue Count</dt>
	<dd><?= $rogueCount ?></dd>
	
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