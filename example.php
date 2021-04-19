<?php include 'bracket/bracket_gen.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="image/jpeg; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Untitled Document</title>

<link href="bracket/brackets.css"   rel="stylesheet">
<link href="bracket/connectors.css" rel="stylesheet">

</head>
<body>
<div class="bracket-wrapper">
    <div class="bracket">
        <?php 

            $players = $_GET['players'];
            if($players <= 0)  $players = 16;

             require_once('bracket/arpBracket.php');
             $brackets  = new arpBracket( );             
             $play_bronze = true;             
             // generates a bracket with 16 round and 3rd place match
             $brackets->build_empty_brackets($players, $play_bronze);
      ?>
    </div>
</div>

<hr>
<h4>Generates 16 matches without 3rd place match</h4>
<div class="bracket-wrapper">
    <div class="bracket">
        <?php 
             require_once('bracket/arpBracket.php');
             $play_bronze = false;
             $brackets  = new arpBracket( );
             $brackets->build_empty_brackets($players, $play_bronze);
      ?>
    </div>
</div>


</body>
</html>