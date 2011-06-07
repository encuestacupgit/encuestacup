<?php

/* $Id: reset.inc 652 2003-03-28 03:42:47Z jimmerman $ */

/* vim: set tabstop=4 shiftwidth=4 expandtab: */

// Written by James Flemer
// For eGrad2000.com
// <jflemer@alum.rpi.edu>

	@session_unregister('survey_id');
	@session_destroy();
	include(esp_where('index'));
?>
