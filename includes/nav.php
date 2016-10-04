<div id="nav">
	<ul>
		<li><a href="/">Humans Vs Zombies</a></li>
		<li><a href="/index.php?page=rules">Rules</a></li>
		<li><a href="/index.php?page=staff">Staff</a></li>
		<li><a href="/index.php?page=stats">Stats</a></li>
		<li><a href="https://instagram.com/apuhvz/" target="_blank">Instagram</a></li>
		<li><a href="https://twitter.com/APUHvZ" target="_blank">Twitter</a></li>
		<li><a href="https://www.facebook.com/apu.hvz" target="_blank">Facebook</a></li>
		<?php
			if($enter_kill)//if enter_kill flag is true displays the link for the enter_kill page.
				echo("<li><a href='/index.php?page=enter_kill'>Enter Kill</a></li>");
			if($profile)//if profile flag true display link
				echo("<li><a href='/index.php?page=profile'>Profile</a></li>");
			if($admin)//if admin display admin link
				echo('<li><a href="admin/admin.php">Admin</a></li>');
		?>
	</ul>
</div>