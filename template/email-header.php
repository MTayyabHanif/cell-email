<?php
	// get the options
	$email_options = get_option('cell_email_base_options');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta property="og:title" content="<?php echo $email_subject ?>" />
		<title><?php echo $email_subject ?></title>
	</head>
	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width: 100% !important; -webkit-text-size-adjust: none; margin-top: 0; margin-right: 0; margin-bottom: 0; margin-left: 0; padding-top: 0; padding-right: 0; padding-bottom: 0; padding-left: 0; background-color: #FAFAFA;" bgcolor="#FAFAFA">
		<center>
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="height: 100% !important; width: 100% !important; margin-top: 0; margin-right: 0; margin-bottom: 0; margin-left: 0; padding-top: 0; padding-right: 0; padding-bottom: 0; padding-left: 0; background-color: #FAFAFA;" bgcolor="#FAFAFA">
				<tr><td align="center" valign="top" style="padding-top: 20px; border-collapse: collapse;">
					<table border="0" cellpadding="0" cellspacing="0" width="600" style="border-top-color: #dddddd; border-right-color: #dddddd; border-bottom-color: #dddddd; border-left-color: #dddddd; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color: #FFFFFF;" bgcolor="#FFFFFF">
						<?php if (isset($email_options['email_header'])): ?>
							<tr><td align="center" valign="top" style="border-collapse: collapse;">
								<table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #FFFFFF; border-bottom-width: 0;" bgcolor="#FFFFFF">
									<tr><td style="border-collapse: collapse; color: #202020; font-family: Arial; font-size: 34px; font-weight: bold; line-height: 100%; text-align: center; vertical-align: middle; padding-top: 0; padding-right: 0; padding-bottom: 0; padding-left: 0;" align="center" valign="middle">
										<img src="<?php echo $email_options['email_header'] ?>" style="max-width: 600px; height: auto; line-height: 100%; outline: none; text-decoration: none; border-top-width: 0; border-right-width: 0; border-bottom-width: 0; border-left-width: 0;" />
									</td></tr>
								</table>
							</td></tr>
						<?php endif ?>
						<tr><td align="center" valign="top" style="border-collapse: collapse;">
							<table border="0" cellpadding="0" cellspacing="0" width="600">
								<tr><td valign="top" style="border-collapse: collapse;">
									<table border="0" cellpadding="20" cellspacing="0" width="100%">
										<tr><td valign="top" style="border-collapse: collapse; background-color: #FFFFFF;" bgcolor="#FFFFFF">
											<div style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;" align="left">
												<h1 style="color: #202020; display: block; font-family: Arial; font-size: 34px; font-weight: bold; line-height: 100%; text-align: left; margin-top: 0; margin-right: 0; margin-bottom: 10px; margin-left: 0;" align="left"><?php echo $email_subject ?></h1>