<?php
/**
*
* @package adm
* @version $Id: l_rules.php 2062 2012-10-17 05:18:36Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}

//for style ..
$stylee	= "admin_rules";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');

$affected = false;
$H_FORM_KEYS	= kleeja_add_form_key('adm_rules');

//
// Check form key
//
if (isset($_POST['submit']))
{
	if(!kleeja_check_form_key('adm_rules'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}


$query	= array(
				'SELECT'	=> 'rules',
				'FROM'		=> "{$dbprefix}stats"
			);

$result = $SQL->build($query);

while($row=$SQL->fetch_array($result))
{
	$rulesw = isset($_POST['rules_text']) ? $_POST['rules_text'] : $row['rules'];
	$rules = htmlspecialchars($rulesw);
			
	//when submit
	if (isset($_POST['submit']))
	{
		//update
		$update_query	= array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "rules = '" . $SQL->real_escape($rulesw) . "'"
							);

		$SQL->build($update_query);
		if($SQL->affected())
		{
			$affected = true;
			delete_cache('data_rules');
		}
	}
}

$SQL->freeresult($result);


//after submit 
if (isset($_POST['submit']))
{
	$text	= ($affected ? $lang['RULES_UPDATED'] : $lang['NO_UP_CHANGE_S']);
	$text	.= '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') .  '\');", 2000);</script>' . "\n";
	$stylee	= "admin_info";
}
