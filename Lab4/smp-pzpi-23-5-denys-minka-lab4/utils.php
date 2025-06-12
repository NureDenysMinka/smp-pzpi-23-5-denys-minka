<?php

function redirectToNotFound(string $path): void
{
	if (isset($_SESSION['username'])) {
		include $path;
	} else {
		include 'pages/404.phtml';
	}
}
