if(Object.isUndefined(Rsvp))
	var Rsvp = {};
if(Object.isUndefined(Rsvp.Index))
	Rsvp.Index = {};

//M////C/////A//
Rsvp.Index.Index = {
	form: 'rsvp',
	guests: 'guests',
	defaultGuestName: '',
	guestClassName: 'guest',
	yesClassName: 'yes',
	clone: null,
		
	initialize: function()
	{
		var self = Rsvp.Index.Index;
		self.form = $(self.form);
		self.guests = $(self.guests);
		
		self.form.observe('submit', self.form_Submit);
		
		var clone = self.guests.down('.'+self.guestClassName);
		// create master clone
		self.clone = clone.cloneNode(true);
	},
	
	form_Submit: function (event)
	{
		//event.stop();
		var self = Rsvp.Index.Index;
		var guestRows = self.form.select('.'+self.guestClassName);
		
		guestRows.each(function(guestRow) {
			// if they're not attending, we're not going to validate
			// the ^ says give us the beginning of the attrib value
			// (the selector doesn't support nested square brackets)
			var attending = guestRow.down('input[name^=attending]');
			if(!$F(attending)) return;
			
			var guestName = guestRow.down('input[name^=guestName]');
			if($F(guestName) == self.defaultGuestName)
			{
				alert("Please use a real name.  Thanks!");
				guestName.select();
				return event.stop();
			}
			
			var required = guestRow.select('.required');
			var missing = required.reject($F);
			
			if(missing.length)
			{
				var missingTitles = missing.pluck('title');
				alert("Be sure to fill in:\n" + missingTitles.join(",\n"));
				missing.first().focus();
				return event.stop();
			}
		});
	},
	
	label_Click: function()
	{
		var self = Rsvp.Index.Index;
		var checkbox = this.next('input[type=checkbox]');
		checkbox.checked = !checkbox.checked;
		checkbox.onchange();
	},
	
	attending_Change: function()
	{
		var self = Rsvp.Index.Index;
		var yes = this.checked;
		$(this).previous('input[type=hidden]').value = yes ? 1 : '';
		var attending = this.up('.attending');
		attending[yes ? 'addClassName' : 'removeClassName'](self.yesClassName);
	},
	
	
	addGuest: function()
	{
		var self = Rsvp.Index.Index;
		// clone master
		var clone = $(self.clone.cloneNode(true));
		
		self.guests.insert(clone);
		
		var guestName = clone.down('.guestName input.text');
		guestName.value = self.defaultGuestName;
	}
};

document.observe('dom:loaded', Rsvp.Index.Index.initialize);