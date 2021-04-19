<?php
$players = range(1, 32);
$count = count($players);
$numberOfRounds = log($count / 2, 2);

shuffle($players);
// Order players.
for ($i = 0; $i < $numberOfRounds; $i++) {
	$out = array();
	$splice = pow(2, $i); 

	while (count($players) > 0) {

		$out = array_merge($out, array_splice($players, 0, $splice));
		$out = array_merge($out, array_splice($players, -$splice));

	}            

	$players = $out;
}



function add_match($round, $match, $no_brackets = false)
{	
	if( $match % 2 == 0 ):
	  $connector = "bottom";
	else:
	  $connector = "top";
	endif;
	
	$data = '
	 <div class="match match-'.$round.'-'.$match.'">
      <div class="team-container">';
	$data .='	
        <div class=" team top " data-teamid="">
          <div class="label">TDB</div>
          <div class="score"  data-resultid="result-1">2</div>
		  <div class="pk"  data-resultid="result-1"><small><sup>5-4pk</sup></small></div>		
        </div>

        <div class="team bottom" data-teamid="">
          <div class="label">TDB</div>
          <div class="score"  data-resultid="result-1">2</div>	
		  
        </div>';
		if(!$no_brackets):
			$data .= '<div class="connector connector-'.$connector.'-'.$round.'">';
		 			 if($connector == 'top')
			  			 $data .=  '<div class="connector-h c-h'.$round.'"></div>';
   	  		 $data .= '</div>';
		endif;
		$data .='
        <div class="match-details ">view details</div> <!-- REMOVER TEXTO -->
		 <div class="match-details"></div>
      </div>
    </div>';	
	echo $data;
}



function open_round($round,$num_jogos,$title = "")
{	
	$title = "Round" . $round;
	$data = " <div class=\"round round-{$round}\">\n
  	<div class=\"round-title\">
	  {$title}
	  <div class=\"round-matches\">Jogos {$num_jogos}</div>\n
	</div>\n";
	  
	echo $data;
}
function close_round( ){ echo '</div>';}




function id($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  echo $randomString;
}


function build_empty_bracket($players)
{
	$rounds = calculate_rounds($players);
	$final = @count($rounds);

	foreach($rounds as $k => $v):
		$round = $k + 1;		
		$is_final = $round == $final ? true : false;
		open_round($round, $v);	
		for($i=1;$i<=$v;$i++)
			add_match($round,$i,$is_final);
			close_round();
	endforeach;
}


function calculate_rounds($total_players)
{	
		if( $total_players % 16 > 0):
			while($total_players % 16 == 0)
				$total_players++;
		endif;

	$rounds = [];
	$n = null;	
    // max 256 
	for($i=0;$i<8;$i++){
		$n = $n == null ?  $total_players : $n;
		$n = $n / 2;
		$rounds[ ] = $n;
		if($n == 1)
			break;
	}
	return $rounds;
}





 

 // Print match list.
 
//  for ($i = 0; $i < $count; $i++) {
// 	printf('%s vs %s<br />%s', $players[$i], $players[++$i], PHP_EOL);
// }



?>