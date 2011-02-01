<script type="text/javascript" src="/js/rsvp/index/widget.js"></script>
<form method="post" action="/rsvp" id="rsvp_index_widget">
	<input 
		type="text" 
		class="text"
		name="name"
		value="Enter Your Name"
		onfocus="$(this).addClassName('active'); $(this).select()"
		onblur="$(this).removeClassName('active')" />
	<button type="submit" class="submit">RSVP!</button>
	<p>Please use your invitation for correct spelling.  Thanks!</p>
</form>