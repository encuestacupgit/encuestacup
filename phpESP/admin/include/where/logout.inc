<?php

/* $Id: logout.inc 952 2007-12-10 12:15:55Z liedekef $ */

/* vim: set tabstop=4 shiftwidth=4 expandtab: */

// Written by James Flemer
// <jflemer@alum.rpi.edu>
// <jflemer@acm.rpi.edu>

	manage_logout();
?>
<table cellspacing="0" cellpadding="4">
	<tr>
		<td><?php echo _('Logged out.'); ?><br>
			<?php echo("<a href=\"". $GLOBALS['ESPCONFIG']['ME'] ."?where=manage\">" . _('Log back in.') . "</a>\n"); ?>
		</td>
	</tr>
</table>
&nbsp;
