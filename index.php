<?php

//============================================================+
// File Name   : index.php
// Begin       : 2014-08-01
// Last Update : 2017-10-25
// 
// Author: Mike Ludemann
// 
//============================================================+

//include('init.php');

$verzeichnis = 'files/';

$url = 'https://github.com/Fyrd/caniuse/tree/master/features-json';
$html = file_get_contents($url);
$links = array();

if (preg_match_all('/<a .*title=\"(.*?)\"/', $html, $links)) {
    $links = $links[1];
}

?>

<!-- Overview of the form -->
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include('include/meta.html'); ?>
	<title>Can I Use - Overview</title>
</head>
<body>
    <div class="header">
        <?php include('include/header.html'); ?>
    </div>
    <div class="content">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div id="search_filter">
					<form target="check" action="evaluation.php" method="POST">
						<fieldset>
						<legend>Data Matching</legend>
						<div class="row">
							<div class="col-xs-12 col-sm-8 col-md-8">
							<select name="art" size="1" class="w100">
								<optgroup label="Capability">

								<?php
								foreach ($links as $link)
								{
									if (strpos($link,".json") !== false)
									{
										$t = substr($link, 0, -5);
									?>
									<option value="<?php echo $t; ?>">

										<?php

										echo $t;

										?>

									</option>';
									<?php
									}
								}
								?>

								</optgroup>
							</select>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4 right">
							<input type="submit" name="submit" value="Data Matching" class="btn btn-default btn-xs">
							</div>
						</div>
						</fieldset>
					</form>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div>
					<fieldset>
						<legend>Result</legend>
						<iframe name="check" id="caniuse" src="evaluation.php"></iframe>
					</fieldset>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="footer">
        <?php include('include/footer.html'); ?>
    </div>
</body>
</html>
<?php
//}
?>
