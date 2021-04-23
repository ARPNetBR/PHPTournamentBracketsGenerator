<?php  
 require_once('bracket/arpBrackets.php'); 
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
<style>
.trophy-gold{
  color:#d4af37;
  font-size:2em
}
.trophy-silver{
  color:#aaa9ad ;
  font-size:2em
}
.trophy-bronze{
  color:#cd7f32;
  font-size:2em
}
.podium-place{
  border:1px solid #ddd;
  padding:.5em 1em;
  
}
.first{
  border-top-right-radius:5px;
  border-top-left-radius:5px;
  border-bottom:0;
}
.third{
  border-bottom-right-radius:5px;
  border-bottom-left-radius:5px;
  border-top:0;
  padding-left:1.4em
}
.winner-img{
  width:40px;
  height:40px;
  border-radius:50px;
 background-color:#ddd;
 /* padding-top:1em */
}
</style>

        <?php 
            
            $players = @$_GET['players'];
            if($players <= 0)  $players = 16;
            $bronze =  @$_GET['bronze'];                  
     
  $dummy = new dummyData();
  $brackets1  = new arpBrackets(  );  
  $brackets  = new arpBrackets(  );  
 
  // set number of players or u just can pass it to constructor
  $brackets->set_players( $players ); 

  // enable/disable bronze round
  $brackets->set_bronze_round($bronze); 

  // display podium after last match
  $brackets->show_podium(false);

   // enable css calculation on the fly
  $brackets->css_on_the_fly(true);

    // set header match label
  $brackets->set_match_label('Games'); 

    // set header std round label + round number eg. Round 1 , Round 2 and so on    
  $brackets->set_round_label('Round'); 

    // enable/disable round title to be shown
  $brackets->set_titles(false); 

  
    // calculate number of rounds
  $brackets->calculate_rounds();
  
  $game_data = $dummy->get_dummy_data( $brackets->get_rounds() );
  
  // $brackets->set_direction($brackets::RIGHT2LEFT); // set rtl direction
    // build empty single elimination bracket
  $brackets->draw_single_elimination( $game_data );  
   return;
  
                                        // retrieve rounds to add game data
  $game_data = $dummy->get_dummy_data( $brackets->get_rounds() );

  
  $brackets1->set_players( $players ); 
  // enable/disable bronze round
  $brackets1->set_bronze_round($bronze); 
  // display podium after last match
  $brackets1->show_podium(false);
   // enable css calculation on the fly
  $brackets1->css_on_the_fly(true);
    // set header match label
  $brackets1->set_match_label('Games'); 
    // set header std round label + round number eg. Round 1 , Round 2 and so on    
  $brackets1->set_round_label('Round'); 
    // enable/disable round title to be shown
  
    // calculate number of rounds
  $brackets1->calculate_rounds();   
   // enable/disable round title to be shown
   $brackets1->set_titles(true); 
  $brackets1->set_direction($brackets::RIGHT2LEFT); // set rtl direction
  // build single elimination bracket with match data
  $brackets1->draw_single_elimination($game_data);
?>


</body>
</html>