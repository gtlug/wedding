<?php 
$this->headStyle()->appendStyle('@import url(/css/rsvp.css);');
?>
<script type="text/javascript" src="/js/rsvp/index/index.js"></script>
<script type="text/javascript">
Rsvp.Index.Index.defaultGuestName = "<?= $this->defaultGuestName ?>";
</script>

<h1>Thank You For RSVP-ing <?= $this->invite->mailingName ?>!</h1>

<form id="rsvp" method="post" action="/rsvp/index/do">
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
			<?php if(!$this->invite->inviteId) { ?>
			<a href="javascript:Rsvp.Index.Index.addGuest()">Add Guest</a><br /><br />
			<?php } /*if(!invite)*/ ?>
			<button type="submit" class="submit">Save Changes</button>
		</td>
	</tr>
</tfoot>
<tbody id="guests">
<?php foreach($this->guests as $guest) { ?>
	<tr class="guest">
		<td class="attending yes" style="text-align: right;">
			<label onclick="Rsvp.Index.Index.label_Click.bind(this)()">
				<span class="yes">Yes</span>
				<span class="no">No</span>
			</label>
			
			<input 
				type="hidden" 
				class="hidden"
				name="guestId[]"
				value="<?= $guest->guestId ?>" />
				
			<input 
				type="hidden" 
				class="hidden"
				name="attending[]"
				value="1" 
				title="Attending" />
				
			<input 
				type="checkbox" 
				class="checkbox"
				onchange="Rsvp.Index.Index.attending_Change.bind(this)()"
				onclick="this.blur();" 
				checked="checked"
				title="Attending" />
		</td>
		
		<td class="guestName">
			<input
				type="text"
				class="text required"
				name="guestName[]"
				value="<?= $guest->guestName ?>"
				onclick="$(this).select();"
				title="Guest's Name"
				/>
		</td>
		
		<td class="foodId" style="font-size: smaller; padding: 7px;">
			<select
				name="foodId[]" 
				size="<?= count($this->foods) ?>"
				class="required"
				title="Food Choice" />
				
		<?php foreach($this->foods as $food) { ?>

				<option value="<?= $food->foodId ?>"><?= $food->foodName ?></option>
		<?php } /*foreach(foods)*/ ?>
			</select>
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