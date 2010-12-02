<form method="post" action="/rsvp/index/do">
<input type="hidden" class="hidden" name="inviteId" value="<?= $this->invite->inviteId ?>" />
<table border="0" cellpadding="0" cellspacing="0">
<?php foreach($this->guests as $guest) { ?>
	<tr class="guest">
		<td class="attending">
			<input 
				type="hidden" 
				class="hidden"
				name="guestId[]"
				value="<?= $guest->guestId ?>" />
				
			<input 
				type="hidden" 
				class="hidden"
				name="attending[]"
				value="1" />
				
			<input 
				type="checkbox" 
				class="checkbox"
				onchange="$(this).previous('input[type=hidden]').value = this.checked ? 1 : ''"
				onclick="this.blur();" 
				checked="checked" />
		</td>
		
		<td class="guestName">
			<input
				type="text"
				class="text"
				name="guestName[]"
				value="<?= $guest->guestName ?>"
				/>
		</td>
		
		<td class="foodId">
		<?php foreach($this->foods as $food) { ?>
			<input
				id="foodId<?= $this->id()->increment() ?>"
				type="radio"
				class="radio"
				name="foodId[]"
				value="<?= $food->foodId ?>"
				/>
			<label for="foodId<?= $this->id() ?>"><?= $food->foodName ?></label>
		<?php } /*foreach(foods)*/ ?>
		</td>
		
	</tr>
<?php } ?>
</table>
</form>

<dl>
<?php foreach($this->foods as $food) { ?>
	<dt><?= $food->foodName ?></dt>
		<dd><?= $food->foodDesc ?></dd>
<?php } /*foreach(foods)*/ ?>
</dl>