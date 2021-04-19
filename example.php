<?php  
 require_once('bracket/arp.php');
 require_once('bracket/arpBracket.php');
 require_once('bracket/dummyData.php');

?>
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
            // $dummy = new dummyData();
            // $brackets  = new arpBrackets(  );  
            $players = @$_GET['players'];
            if($players <= 0)  $players = 16;

            $bronze =  @$_GET['bronze'];
            // $play_bronze = true;   

            // $game_data = $dummy->generate_dummy_data($players,$play_bronze)    ;         
            // $brackets->build_brackets($game_data,$play_bronze);
      ?>
    </div>
</div>

<hr>
<!-- ********************************************************************* -->
<?php 
  $dummy = new dummyData();
  $brackets  = new arpBracket(  );  
  $brackets->set_players( $players ); // set number of playres

  $brackets->set_bronze_round($bronze); // enable bronze round

  $brackets->css_on_the_fly(true); // enable css calculation on the fly

  $brackets->set_match_label('Jogos');

  $brackets->set_round_label('Fase'); // if needed, must be called before set_titles

  $brackets->set_titles(true); // enable round title

//   $brackets->set_round_title(...,..); not need to invoce set_titles, it enables  
  $brackets->set_direction('rtl'); // calculate number of rounds  

  $brackets->calculate_rounds(); // calculate number of rounds
  $rounds = $brackets->get_rounds();  

  $game_data = $dummy->get_dummy_data( $brackets->get_rounds() );

 
  $brackets->draw_single_elimination( );  
  $brackets->draw_single_elimination($game_data);

//   var_dump(($rounds));
//   var_dump(array_reverse($rounds));
//   return;

?>
</body>
</html>