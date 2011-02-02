if(Object.isUndefined(Rsvp))
	var Rsvp = {};
if(Object.isUndefined(Rsvp.Index))
	Rsvp.Index = {};

//M////C/////A//
Rsvp.Index.Widget = {
	form: 'rsvp_index_widget',
	input: null,
	defaultName: '',
		
	initialize: function()
	{
		var self = Rsvp.Index.Widget;
		self.form = $(self.form);
		self.input = self.form.down('input[name=name]');
		
		self.input.observe('focus', self.input_Focus);
		self.input.observe('blur', self.input_Blur);
		self.form.observe('submit', self.form_Submit);
		
		self.defaultName = self.input.readAttribute('value');
	},
	
	form_Submit: function (event)
	{
		var self = Rsvp.Index.Widget;
		var name = $F(self.input);
		
		if(!name || name == self.defaultName)
		{
			alert('Please enter your name');
			return event.stop();
		}
		
		var regex = /\w+/g;
		var parts = name.match(regex);
		
		if(parts.length < 2)
		{
			alert("Please use your full name");
			return event.stop();
		}
	},
	
	input_Focus: function()
	{
		var self = Rsvp.Index.Widget;
		var name = $F(self.input);
		
		if(name == self.defaultName)
		{
			self.input.value = '';
		}
	},
	
	input_Blur: function()
	{
		var self = Rsvp.Index.Widget;
		var name = $F(self.input);
		
		if(!name)
		{
			self.input.value = self.defaultName;
		}
	}
};

document.observe('dom:loaded', Rsvp.Index.Widget.initialize);