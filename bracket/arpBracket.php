<?php 
/**
 * Name:    Arp Brackets
 * Author:  Andre Pereira
 *           aroberto27@gmail.com
 
 * Created:  18.04.2021
 *
 * Description:  Easy way to generate tournament brackets using only php and styles sheets no js is required 
 *
 * Requirements: PHP5.6 or above
 *
 * @package    PHPTournamentBracketsGenerator
 * @author     Andre Pereira
 * @link       https://github.com/ARPNetBR/PHPTournamentBracketsGenerator
 */

class arpBracket {
    

    
    public function __construct() 
    {
       #code
    }


  /**
   *  Build empty brackets 
   * @param integer $players
   * @param boolean $play_bronze  
   */    
  public function build_empty_brackets($players = 256, $play_bronze = true)
  {
    $rounds = $this->calculate_rounds($players,$play_bronze);

    $this->add_bracket_style($rounds);
    
    if($play_bronze):
      $this->build_rounds_w_bronze($rounds, true);
    else:
      $this->build_rounds($rounds, true);
    endif;
  }

  /**
   *  Build  brackets with game data
   * @param array $rounds
   * @param boolean $play_bronze  
   */ 
  public function build_brackets($rounds,$play_bronze)
  {   
    
    $this->add_bracket_style($rounds);

    if($play_bronze):
      $this->build_rounds_w_bronze($rounds);
    else:
      $this->build_rounds($rounds);
    endif;
  }

 /**
     * Calculate number or rounds, based on total players
     * @param integer $total_players
     * @param boolean $play_bronze
     * @return number of rounds     
     */
    public function calculate_rounds($total_players,$play_bronze)
    {	
        if( $total_players % 16 > 0):
          while($total_players % 16 == 0)
            $total_players++;
        endif;
    
      $rounds = [];
      $n = null;	
      $key = -1;

        // max 256 players .. to allow more increase the $i<8
      for($i=0;$i<8;$i++){
        $n = $n == null ?  $total_players : $n;        
        $n = $n / 2;

        $key++;

        if($n == 1):
          if($play_bronze){
            $rounds[$key]['matches'] = 2;
            $rounds[$key]['game_data'] = array();
          }            
          else{
            $rounds[$key]['matches'] = $n;
            $rounds[$key]['game_data'] = array();
          }                      
          break;
        endif;
          $rounds[$key]['matches'] = $n;
          $rounds[$key]['game_data'] = array();
        }
      return $rounds;
    }
   

    /**
     * build rounds with 3rd place  in the brackets
     * @param array $rounds
     * @return string 
     */
    protected function build_rounds_w_bronze($rounds,$game_data_empty = false)
    { 
      $total_rounds = @count($rounds);
     
      foreach($rounds as $k => $v):
        $matches = $v['matches'];
        $round = $k + 1;				
        $is_final = $round == $total_rounds ? true : false; // check if is final round
        
        $this->open_round($round, $matches, 'Round ' . $round);	
        
        for($i=1;$i<=$matches;$i++):
          $j = $i - 1;
          $c = ( $is_final && $i == $matches ) ? 'semi' : $i; // if is bronze match change the mathc class to -semi
          $game_data = $game_data_empty ? '' : $v['game_data'][$j];

          $this->add_match($round,$c,$is_final, $game_data );
        endfor;
  
        $this->close_round();
      endforeach;
    }

     /**
     * build rounds without 3rd place in the brackets
     * @param array $rounds
     * @return string 
     */
    protected function build_rounds($rounds,$game_data_empty = false)
    {      
      $final = @count($rounds);

		  foreach($rounds as $k => $v):
        $matches = $v['matches'];
			  $round = $k + 1;				
			  $is_final = $round == $final ? true : false; // check if is final round
			  $this->open_round($round, $matches, 'Round ' . $round);	
      
			  for($i=1;$i<=$matches;$i++):
          $j = $i - 1;
          $game_data = $game_data_empty ? '' : $v['game_data'][$j];

          $this->add_match($round, $i, $is_final, $game_data );
        endfor;
			  $this->close_round();
		  endforeach;
    }

    /**
     * @param integer $round
     * @param integer $match_no
     * @param string  $round_title
     * @return string
     */
    protected function open_round($round, $match_no, $round_title)
    {     
      $data = " <div class=\"round round-{$round}\">\n
        <div class=\"round-title\">
        {$round_title}
        <div class=\"round-matches\">Matches {$match_no}</div>\n
      </div>\n";
        
      echo $data;
    }

    /**
     * @return string
     */
    protected function close_round()
    {
      echo "</div>";
    }


    /**
     * @param array $rounds 
     * @param boolean $write
     * @return string brackets
     * to increase performance, can be generate the style for all 256 matches 
     * and add to a .css file
     */
    protected function add_bracket_style($rounds)
    {
      $style = "<style>\n";
      $size = @count($rounds);
      unset($rounds[0]);

      $len = ( 20 * $size ) ;
      $style .= ".bracket{width: {$len}em;overflow-x:auto}\n";

      foreach($rounds as $key => $val){
        $r = $key + 1;
        for($i=1;$i<=$val['matches'];$i++):
          $margin = $this->round_step($r,$i);
          $style .= ".round .match-{$r}-{$i}{ margin-top:{$margin}em; }\n";
        endfor;
      }
      $style .= "</style>";
      echo $style;    
    }

    /** #return distance between each match according the round 
     * @param integer $round
     * @param integer $match
    */
    protected function round_step($round,$match)
    { 
        switch($round):
          case 1:
            return; // not need for 1st round
          case 2:
            if($match == 1)
              return 7;
            else 
              return ( 7 + (20 * ( $match - 1 )));

          case 3:
            if($match == 1)
              return 17;
            else 
              return ( 17 + (40 * ( $match - 1 )));

          case 4:
            if($match == 1)
              return 37;
            else 
              return ( 37 + (80 * ( $match - 1 )));

          case 5:
            if($match == 1)
              return 77;
            else 
              return ( 77 + (160 * ( $match - 1 )));

          case 6:
            if($match == 1)
              return 157;
            else 
              return ( 157 + (320 * ( $match - 1 )));

          case 7:
            if($match == 1)
              return 317;
            else 
              return ( 317 + (640 * ( $match - 1 )));

          case 8:
              return 637; // reached max number of matches
        
          default:
          return 0; // breached max size of 256 matches
        endswitch;

     
    }

    /**
     * @param integer $round
     * @param string $match
     * @param boolean $final_round
     */
    protected function add_match($round, $match,  $final_round = false, $game_data)
    {	
      $connector = '';
      $team1 ="&nbsp;";
      $team2="&nbsp;";
      $score1 ="--"; 
      $score2 ="--";
      $winner = '';
      $tiebreaker ="";
      $details = "";
     
      if(isset( $game_data['team_1'] )):
        $team1      = $game_data['team_1'];
        $team2      = $game_data['team_2'];
        $score1     = $game_data['score_1']; 
        $score2     = $game_data['score_2'];
        $winner     = $game_data['winner'];
        $tiebreaker = $game_data['tiebreaker'];
        $details    = $game_data['details'];
      endif;

      if(!$final_round) : 
        if( $match % 2 == 0 ):
          $connector = "bottom";
        else:
          $connector = "top";
        endif;
      endif;

      $match_count = $win1 = $win2 = $breaker1 = $breaker2 ="";

      if($winner == 1){ 
        $win1 = 'team-win';
        $breaker1 = $tiebreaker;
      } 
      if($winner == 2){
        $win2 = 'team-win';
        $breaker2 = $tiebreaker;
      } 
	
      $data = '
      <div class="match match-'.$round.'-'.$match.'">
          <div class="team-container">';
      $data .='	
            <div class=" team top '.$win1.'" data-teamid="">
              <div class="label">'.$team1.'</div>
              <div class="score"  data-resultid="">'.$score1.'</div>
              <div class="pk"><small><sup>'.$breaker1.'</sup></small></div>
            </div>

            <div class="team bottom '.$win2.'" data-teamid="">
              <div class="label">'.$team2.'</div>
              <div class="score"  data-resultid="result-1">'.$score2.'</div>	
              <div class="pk"><small><sup>'.$breaker2.'</sup></small></div>
            </div>';
      
        if(!$final_round):
          $data .= '<div class="connector connector-'.$connector.'-'.$round.'">';
              if($connector == 'top')
                  $data .=  '<div class="connector-h c-h'.$round.'"></div>';
              $data .= '</div>';
        endif;
        $data .='
            <div class="match-details ">'.$details.'</div>
        <div class="match-details"></div> <!-- spacer -->
          </div>
        </div>';	
      echo $data;
    }

    
  }