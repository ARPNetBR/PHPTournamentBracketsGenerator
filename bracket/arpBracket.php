<?php 

class arpBracket {
    

    
    public function __construct() 
    {
       
    }


  /**
   *  build empty brackets   
   */    
  public function build_empty_brackets($players = 256, $play_bronze = true)
  {
    $rounds = $this->calculate_rounds($players,$play_bronze);
    
    $this->add_bracket_style($rounds);

    if($play_bronze):
      $this->build_rounds_w_bronze($rounds);
    else:
      $this->build_rounds($rounds);
    endif;
  }

    /**
     * @return number of rounds     
     */
    protected function calculate_rounds($total_players,$play_bronze)
    {	
        if( $total_players % 16 > 0):
          while($total_players % 16 == 0)
            $total_players++;
        endif;
    
      $rounds = [];
      $n = null;	
        // max 256 players .. to allow more increase the $i<8
      for($i=0;$i<8;$i++){
        $n = $n == null ?  $total_players : $n;
        $n = $n / 2;
      // $rounds[ ] = $n;
        if($n == 1):
          if($play_bronze)
            $rounds[ ] = 2;
          else
            $rounds[ ] = $n;
          break;
        endif;
        $rounds[ ] = $n;
        }
      return $rounds;
    }

    /**
     * build rounds with 3rd place  in the brackets
     */
    protected function build_rounds_w_bronze($rounds)
    { 
      $final = @count($rounds);
      foreach($rounds as $k => $v):

        $round = $k + 1;				
        $is_final = $round == $final ? true : false; // check if is final round
  
        $this->open_round($round, $v, 'Round ' . $round);	
  
        for($i=1;$i<=$v;$i++):
          $c = ( $is_final && $i == $v ) ? 'semi' : $i; // if is bronze match change the mathc class to -semi
          $this->add_match($round,$c,$is_final);
        endfor;
  
        $this->close_round();
      endforeach;
    }

     /**
     * build rounds without 3rd place in the brackets
     */
    protected function build_rounds($rounds)
    {      
      $final = @count($rounds);

		  foreach($rounds as $k => $v):
			  $round = $k + 1;				
			  $is_final = $round == $final ? true : false; // check if is final round
			  $this->open_round($round, $v, 'Round ' . $round);	
			  for($i=1;$i<=$v;$i++)
				  $this->add_match($round,$i,$is_final);
			  $this->close_round();
		  endforeach;
    }

    /**
     * open round and at round/matches counter at top of bracket
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
     * @return html element to close round
     */
    protected function close_round()
    {
      echo "</div>";
    }

    /**
     * @return brackets styles sheet
     */
    protected function add_bracket_style($rounds)
    { 
      $style ="<style>\n";
      $size = @count($rounds);
      unset($rounds[0]);

      $len = ( 20 * $size ) ;
      $style .= ".bracket{width: {$len}em;overflow-x:auto}\n";

      foreach($rounds as $key => $len){
        $r = $key + 1;
        for($i=1;$i<=$len;$i++):
          $margin = $this->round_step($r,$i);
          $style .= ".round .match-{$r}-{$i}{ margin-top:{$margin}em; }\n";
        endfor;
      }

      // .round .match-2-1{ margin-top:7em; }

      $style .= "</style>";
     echo $style;
    
    }

    /** #return distance between each match according the round */
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

    protected function add_match($round, $match,  $final_round = false)
    {	
      $connector = '';
      if(!$final_round) : 
        if( $match % 2 == 0 ):
        $connector = "bottom";
        else:
        $connector = "top";
        endif;
      endif;

	    $match_count = '';
	
      $data = '
      <div class="match match-'.$round.'-'.$match.'">
          <div class="team-container">';
      $data .='	
            <div class=" team top " data-teamid="">
              <div class="label col-10">   </div>
              <div class="score"  data-resultid="result-1">--</div>
        <!-- <div class="pk"  data-resultid="result-1"><small><sup>5-4pk</sup></small></div> -->
            </div>

            <div class="team bottom" data-teamid="">
              <div class="label col-10">  </div>
              <div class="score"  data-resultid="result-1">--</div>	
          
            </div>';
      
        if(!$final_round):
          $data .= '<div class="connector connector-'.$connector.'-'.$round.'">';
              if($connector == 'top')
                  $data .=  '<div class="connector-h c-h'.$round.'"></div>';
              $data .= '</div>';
        endif;
        $data .='
            <div class="match-details "></div> <!-- REMOVER TEXTO -->
        <div class="match-details"></div> <!-- spacer -->
          </div>
        </div>';	
      echo $data;
  }
    
  }