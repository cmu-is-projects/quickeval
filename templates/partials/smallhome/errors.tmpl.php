<?php
	if (isset($_SESSION['errorflash'])){
		$out = "<p class='error'><img src='/ui/images/icons/warning.png' height='16px' width='16px'>";
		foreach ($_SESSION['errorflash'] as $error){
			$out .= "$error<br />";
		}
		$out .= "</p>";
		
		unset($_SESSION['errorflash']);
		echo $out;
	}
	if (isset($_SESSION['noticeflash'])){
		$out = "<p class='notice'><img src='/ui/images/icons/yes.png' height='16px' width='16px'>";
		foreach ($_SESSION['noticeflash'] as $error){
			$out .= "$error<br />";
		}
		$out .= "</p>";
		
		unset($_SESSION['noticeflash']);
		echo $out;
	}

?>