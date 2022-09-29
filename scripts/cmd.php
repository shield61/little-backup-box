<!-- Author: Stefan Saam, github@saams.de
License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->
<!doctype html>

<?php
$WORKING_DIR=dirname(__FILE__);
$config = parse_ini_file($WORKING_DIR . "/config.cfg", false);

$theme = $config["conf_THEME"];
$background = $config["conf_BACKGROUND_IMAGE"] == ""?"":"background='/img/backgrounds/" . $config["conf_BACKGROUND_IMAGE"] . "'";

# expected parameters:
# CMD: "update", "format"
# optional parameters:
# format: PARAM1:[partition, e.g. 'sda1'] PARAM2:[FSTYPE]

?>

<html lang="<?php echo $config["conf_LANGUAGE"]; ?>" data-theme="<?php echo $theme; ?>">

<head>
	<?php include "${WORKING_DIR}/sub-standards-header-loader.php"; ?>
</head>

<body <?php echo $background; ?>>
<?php include "${WORKING_DIR}/sub-standards-body-loader.php"; ?>

<?php

if (count($_GET) > 0) {
	extract($_GET);
} else {
	extract($_POST);
}

if (! isset($CMD)) {$CMD	= "";}

switch($CMD) {
	case 'update':
		$CMD_HEADER			= L::cmd_update_header;
		$INFO_TEXT			= L::cmd_update_warning;
		$CMD_DESCRIPTION	= "";
		$CMD_ARGUMENTS		= "CMD=update";
		$PASSWORD_REQ		= True;
		$CMD_LINK_TEXT_HOME	= L::cmd_link_text_home;
		break;

	case 'format':
		$CMD_HEADER			= L::cmd_format_header;
		$INFO_TEXT			= L::cmd_format_warning;
		$CMD_DESCRIPTION	= L::cmd_format_description.": <ul class='danger'><li>" . L::cmd_format_header . ": $PARAM1 &rarr; $PARAM2</li></ul>";
		$CMD_ARGUMENTS		= "CMD=format&PARAM1=$PARAM1&PARAM2=$PARAM2";
		$PASSWORD_REQ		= True;
		$CMD_LINK_TEXT_HOME	= L::cmd_link_text_home;
		break;

	default:
		$CMD_HEADER			= L::cmd_no_cmd;
		$INFO_TEXT			= "";
		$CMD_DESCRIPTION	= "";
		$CMD_ARGUMENTS		= "";
		$PASSWORD_REQ		= False;
		$CMD_LINK_TEXT_HOME	= L::cmd_link_text_home;
}

if ($PASSWORD_REQ) {
	if (isset($PWD)) {
		$PASSWORD_REQ	= (($PWD !== $config['conf_PASSWORD']) or ($PWD == ""));
	}
}
?>

	<h1><?php echo $CMD_HEADER; ?></h1>
	<p>
		<b><u><?php echo $INFO_TEXT; ?></u></b><br>

		<?php echo $CMD_DESCRIPTION; ?><br>
	</p>

	<?php if ($PASSWORD_REQ) { ?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="hidden" name="CMD" value="<?php echo $CMD; ?>">
			<input type="hidden" name="PARAM1" value="<?php echo $PARAM1; ?>">
			<input type="hidden" name="PARAM2" value="<?php echo $PARAM2; ?>">
			<div class="card" style="margin-top: 2em;">
				<label for="PWD"><?php echo L::cmd_input_password; ?>:</label>
				<input type="password" size="20" name="PWD" id="PWD"><br>
				<?php echo l::cmd_input_password_info; ?><br>
				<button style="margin-top: 2em;" type="submit" name="upload_settings"><?php echo L::cmd_execute; ?></button>
			</div>
		</form>
	<?php } elseif ($CMD_ARGUMENTS != "") { ?>
		<iframe id="cmdmonitor" src="/cmd-runner.php?<?php echo $CMD_ARGUMENTS; ?>" width="100%" height="500" style="background: #FFFFFF;"></iframe>
	<?php } ?>

	<p>
		<a href="/"><?php echo $CMD_LINK_TEXT_HOME; ?></a>
	</p>
</body>
</html>
