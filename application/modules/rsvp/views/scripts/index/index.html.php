<h1>Thank You For RSVP-ing <?= $this->invite->mailingName ?>!</h1>

<form method="post" action="/rsvp/index/do">
<input type="hidden" class="hidden" name="inviteId" value="<?= $this->invite->inviteId ?>" />
<table border="0" cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th class="attending">Attending?</th>
		<th class="guestName">Guest Name</th>
		<th class="foodId">Food Choice</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="3">
			<a href="javascript:void()">Add Guest</a><br /><br />
			<button type="submit" class="submit">Save Changes</button>
		</td>
	</tr>
</tfoot>
<tbody>
<?php foreach($this->guests as $guest) { ?>
	<tr class="guest">
		<td class="attending" style="text-align: right;">
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
		
		<td class="foodId" style="font-size: smaller;">
		<?php foreach($this->foods as $food) { ?>

			<input
				id="foodId<?= $this->id()->increment() ?>"
				type="radio"
				class="radio"
				name="foodId[]"
				value="<?= $food->foodId ?>"
				<?= $guest->foodId == $food->foodId ? 'checked="checked"' : '' ?>
				/>
			<label for="foodId<?= $this->id() ?>"><?= $food->foodName ?></label><br />
		<?php } /*foreach(foods)*/ ?>
		</td>
		
	</tr>
<?php } ?>
</tbody>
</table>
</form>

<h3>Food choices</h3>
<dl>
<?php foreach($this->foods as $food) { ?>
	<dt><?= $food->foodName ?></dt>
		<dd><?= $food->foodDesc ?></dd>
<?php } /*foreach(foods)*/ ?>
</dl>