<style type="text/css">

body
{
	top: 0px;
	bottom: 0px;
	right: 0px;
	left: 0px;
	padding-top: 75px;
}

#outer
{
	background-color: black;
	margin: auto; 
	width: 800px; 
	height: 550px; 
	border: 3px solid black;
	border-radius: 15px;
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
}

#left
{
	float: left;
	height: 100%;
	width: 450px;
	color: white;
	border-right: 3px solid black;
	/*border-top-right-radius: 15px;
	border-bottom-right-radius: 15px;
	-moz-border-radius-topright: 15px;
	-moz-border-radius-bottomright: 15px;
	-webkit-border-top-right-radius: 15px;
	-webkit-border-bottom-right-radius: 15px;*/
	position: relative;
}

#left h1, #left h2, #left h3, #left p
{
	position: absolute;
	text-align: center;
	font-family: sans-serif;
	top: 30px;
	margin: 0px;
}

#left p
{
	top: 200px;
	left: 50px;
	right: 50px;
	height: 300px;
	border: white solid 1px;
}

#right
{
	float: right;
	width: 340px;
	padding-right: 5px;
}

#top
{
	height: 200px;
	background-color: grey;
	color: black;
	border-bottom: 3px solid black;
}

#bottom
{
	height: 312px;
	background-color: white;
	color: black;
}


</style>

<div id="outer">

	<div id="left">
		<h1>Adam VanBerlo &amp; Lindsey White <br />Wedding</h1>
		<p>Picture</p>
	</div>
	<div id="right">
		<div id="top">
			<input type="text" />
			<button type="button">RSVP</button>
		</div>
		<div id="bottom">
			<p><a href="#">Visit Registry</a></p>
			<p><a href="/photos">View Photos</a></p>
			<div style="padding-left: 20px;">
<?= $this->action('widget', 'index', 'photos') ?>
			</div>		
		</div>
	</div>
</div>
