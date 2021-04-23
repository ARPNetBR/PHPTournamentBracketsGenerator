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

 

class arpBrackets{

    private $use_css = true; // calculate rounds css on the fly    

    private $max_players = 256; // max players covered, allowed values are 4,8,16,32,64,128,256 

    private $players; 

    private $max_bye_players = 1; // max number of byes, be default we'll use one 

    private $play_bronze_round; // if true generate the bronze round

    private $bracket_type = "single_elimination"; //double_elimination

    private $bracket_diretion = "ltr"; // rtl //2sides

    private $empty_bracket = true;   // generates the bracket empty
 
    private $rounds;        // array with rounds and game data
 
    private $rounds_title; // array with round titles
 
    private $use_titles = false;   // print title on each round

    private $header_match_label = "Matches";  // header label default label + total matches

    private $header_round_label = "Round";   // header label to round def. labal + round no

    private $show_podium   = true;   // enable/disable showing poding after final round

    
 
    public const CSS_MARGIN = [
                [ '0','0' ],     
                [ '0','0' ],        // 1
                [ '7','20' ],       // 2
                [ '17','40' ],      // 3
                [ '37','80' ],      // 4
                [ '77','160' ],     // 5
                [ '157','320' ],    // 6
                [ '317','640' ],    // 7
                [ '634','0' ]      // 8
            ];
    
    public const LEFT2RIGHT = 'ltr';

    public const RIGHT2LEFT = 'rtl';

    public const BOTH       = 'both';

    public const MAX_PLAYERS = 256;

    public const MIN_PLAYERS = 4;

/*************************************************************************************************
 *************************PUBLIC    SECTION*******************************************************  
 *************************************************************************************************/          
    public function __construct($players = 32)
    {  
        try{        
            $this->set_players($players);        
        }catch(Exception $e){        
            echo "Error caught: " . $e->getMessage();        
        }                   
    }
 

    /**
     * 
     */
    public function draw_single_elimination($round_data = null)
    {   
        if($this->bracket_diretion === self::LEFT2RIGHT)
            $this->_single_ltr($round_data);
        if($this->bracket_diretion === self::RIGHT2LEFT)
            $this->_single_rtl($round_data);      
    }
    /**
     * @param integer $num_players
     */
    public function set_players($num_players)
    {   
        $bye_players = 0; 

        if($num_players > self::MAX_PLAYERS)
            throw new Exception("The max. number of players allowed is {self::MAX_PLAYERS}");        

        if($num_players < self::MIN_PLAYERS)
            throw new Exception("The min. number of players allowed is {self::MIN_PLAYERS}");        

        if($num_players > 16 ):
            $mod = 16;
        elseif($num_players > 8):
            $mod = 8;
        else:
            $mod = 4;
        endif;
        /**
         * check//add bye players limit
         */
        if( $num_players % $mod > 0):
            while( ( $num_players % $mod ) > 0) :
                  $bye_players++;
                  $num_players++;
            endwhile;
        endif; 

        if($bye_players > $this->max_bye_players)
             throw new Exception("The max. number of bye players allowed is" . $this->max_bye_players . 'and were created '. $bye_players);

        $this->players = $num_players;
    }
 
    /**
     * if true css for rounds will be calculated and add to page
     * @param boolean $use_css
     */
    public function css_on_the_fly($use_css)
    {
        $this->use_css = $use_css;
    }
    
    /**
     * set bracket direction
     * @param string diretion
     */
    public function set_direction($direction)
    {
        $this->bracket_diretion = $direction;
    }

    /**
     * @param boolean $play_bronze
     */
    public function set_bronze_round($play_bronze = true)
    {
        $this->play_bronze_round = $play_bronze;
    }

     /**
     * set the round title by round number
     * @param integer $round
     * @param string $title
     */
    public function set_round_title($round, $title)
    {
        $this->rounds_title[$round] = $title;
        $this->use_titles = true;
    }

    /**
     * enables round titles
     * @param boolean $var
     */
    public function set_titles($var)
    {
        $this->use_titles = $var;        
        /* init rounds with std name */   
        if($this->use_titles):
            for($i=1; $i<=8; $i++):
                    $this->rounds_title[$i] = "{$this->header_round_label} {$i}";
            endfor;        
        endif;
    }
    /**
     * set the round title by round number     
     * @param array $titles
     */
    public function set_round_title_array($titles)
    {           
        $this->rounds_title = $titles;
    } 

    /**
     * enables/disables podium data
     * @param boolean $var
     * @return void
     */
    public function show_podium($var)
    {
        $this->show_podium = $var;
    }
    /**
     * set default match label
     * @param string $label
     */
    public function set_match_label($label)
    {
        $this->header_match_label = $label;
    }

    /**
     * set default round label
     * @param string $label
     */
    public function set_round_label($label)
    {
        $this->header_round_label = $label;
    }

    /**
     * calculate the number of rounds
     * @return array $rounds
     */
    public function calculate_rounds( )
    {
        $rounds  = [];
        $matches = null;  
        $key     = 0;  

          // max 256 players .. to allow more increase var $i<8
        for($i=0;$i<8;$i++):       
          $matches = $matches == null ?  $this->players : $matches;        
          $matches = $matches / 2;
          $rounds[$key]['round']  = $key + 1;
          if($matches == 1):
            /** if play bronze is true, add an additional match to last round */
            if($this->play_bronze_round):
              $rounds[$key]['matches'] = 2;
              $rounds[$key]['game_data'] = array();            
            else:
              $rounds[$key]['matches'] = $matches;
              $rounds[$key]['game_data'] = array();
            endif;                  
            break;
          endif;
            $rounds[$key]['matches'] = $matches;
            $rounds[$key]['game_data'] = array();
            $key++; 
        endfor;

        $this->rounds = $rounds;
      
    }
 
    /**
     * @return array $rounds
     */
    public function get_rounds( )
    {
        return $this->rounds;
    }

 
    public function get_no_of_rounds( )
    {
        return @count($this->rounds);
    }

/*************************************************************************************************
 *************************PROTECTED SECTION*******************************************************  
 *************************************************************************************************/
    /**
     * draw single elimination bracket ltr
     */
    protected function _single_ltr($round_data)
    {
        
        $rounds = $this->_init_bracket($round_data);

        $total_rounds = count($rounds);
        $game_data = null;
        foreach($rounds as $key => $arr):

            $matches = $arr['matches'];
            $round = $arr['round'];				
            $is_final_round = $round == $total_rounds ? true : false; 
            
            $this->_open_round($round, $matches);	
            
            for($i=1; $i<=$matches; $i++):          

                if($this->play_bronze_round)
                    $con = ( $is_final_round && $i == $matches ) ? 'semi' : $i; // if is bronze match change the mathc class to -semi
                else
                    $con = $i;

                if(!$this->empty_bracket)
                    $game_data = $arr['game_data'][$i];

            $this->_add_match($round,$con,$is_final_round, $game_data );
            endfor;  

            if($is_final_round && $this->show_podium)
                $this->_add_podium(self::LEFT2RIGHT);

            $this->_close_round();
        endforeach;
        $this->_close_bracket( );
    }

     /**
     * draw single elimination bracket rtl
     */
    protected function _single_rtl($round_data)
    {
        
        $rounds = $this->_init_bracket($round_data);
        $rounds = array_reverse($rounds);
        $total_rounds = count($rounds);
        $game_data = null;
        foreach($rounds as $key => $arr):

            $matches = $arr['matches'];
            $round = $arr['round'];				
            $is_final_round = $round == $total_rounds ? true : false; 
            
            $this->_open_round($round, $matches);	
            
            for($i=1; $i<=$matches; $i++):          

                if($this->play_bronze_round)
                    $con = ( $is_final_round && $i == $matches ) ? 'semi' : $i; // if is bronze match change the mathc class to -semi
                else
                    $con = $i;

                if(!$this->empty_bracket)
                    $game_data = $arr['game_data'][$i];

            $this->_add_match($round,$con,$is_final_round, $game_data );
            endfor;  
            
            if($is_final_round && $this->show_podium)
                $this->_add_podium(self::RIGHT2LEFT);

            $this->_close_round();
        endforeach;
     
        $this->_close_bracket( );
    }

    /**
     * init brackets
     * @return array $rounds
     */
    protected function _init_bracket($round_data)
    {
        
        if(is_array($round_data))
            $this->empty_bracket = false;
        // if rounds was not calculated yet, do it    
        if($this->get_no_of_rounds( ) == 0):
            $this->calculate_rounds( );
        endif;

        $this->_write_round_css( );
        $this->_open_bracket( );        

        if($this->empty_bracket):
            $rounds = $this->get_rounds( );            
        else:
            $rounds = $round_data;            
        endif;

        return $rounds;
    }

    /**
     * @return string style sheet
    */
    protected function _write_round_css( )
    {
        
        $style = "<style>\n";
        $size = $this->get_no_of_rounds( );
        $len = ( 20 * $size ) ;
        $style .= ".bracket{width: {$len}em;overflow-x:auto}\n";

        if($this->use_css):
            $rounds = $this->get_rounds( );
             unset($rounds[0]); // remover first round, since no additional style is needed
 
            foreach($rounds as $round => $val):        
                $r = $val['round'];
                for($i=1; $i<=$val['matches']; $i++):
                  $margin = $this->_get_round_css_margin($r,$i);
                  
                  if(!$this->use_titles)
                    $margin = $margin - 2;
                  $style .= ".round .match-{$r}-{$i}{ margin-top:{$margin}em; }\n";
                endfor;
            endforeach;                       
        endif;
           
        $style .= "</style>";       
        echo $style;          
       
    }

    /**
     * calculate the distance between each match according the round
     * @param integer $round
     * @param integer $match
     * @return integer
     */
    protected function _get_round_css_margin($round,$match)
    {      
        list($first,$others) = self::CSS_MARGIN[$round];
        return $match == 1 ? $first : $first + ( $others * ( $match - 1 ));
    }

    /**
     * add match
     * @param integer $round
     * @param string  $match
     * @param boolean $final_round
     * @param array   $game_data
     */
    protected function _add_match($round, $match,  $final_round = false, $game_data = null)
    {	
        $dir = $this->bracket_diretion;  
        $connector = '';
        $team1 ="&nbsp;";
        $team2="&nbsp;";
        $score1 ="--"; 
        $score2 ="--";
        $winner = '';
        $tie1 ="";
        $tie2 ="";
        $details = "";
    
        if($game_data != null ):
            $team1      = $game_data['team_1'];
            $team2      = $game_data['team_2'];
            $score1     = $game_data['score_1']; 
            $score2     = $game_data['score_2'];
            $winner     = $game_data['winner'];
            $tie1       = $game_data['tie1'];
            $tie2       = $game_data['tie2'];
            $details    = $game_data['details'];
        endif;

        $winner_css = 'team-win';
        $loser_css  = '';
        $tie_css    = 'team-win-tie';
        if($final_round):
            $winner_css = 'team-champion';
            $loser_css  = 'team-second';
            $tie_css    = $winner_css;    
        endif;

        /** if not final round , add the bracket connector */
        if(!$final_round) : 
            if( $match % 2 == 0 ):
            $connector = "bottom";          
            else:
            $connector = "top";
            endif;
        endif;

       
        $win1 = $win2 = $loser_css;

        if($winner == 1)
            $win1 = $winner_css;        
        if($winner == 2)
            $win2 = $winner_css;   
        if($tie1 > $tie2)  
            $win1 = $tie_css;        
        if($tie2 > $tie1)  
            $win2 = $tie_css;        


      $data = '
      <div class="match match-'.$round.'-'.$match.'">
          <div class="team-container">';
      $data .='	
            <div class=" team top '.$win1.'" data-teamid="">
              <div class="label">'.$team1.'</div>
              <div class="score"  data-resultid="">'.$score1.'</div>
              <div class="tiebreaker"><small><sup>'.$tie1.'</sup></small></div>
            </div>

            <div class="team bottom '.$win2.'" data-teamid="">
              <div class="label">'.$team2.'</div>
              <div class="score"  data-resultid="">'.$score2.'</div>	
              <div class="tiebreaker"><small><sup>'.$tie2.'</sup></small></div>
            </div>';
      
        if(!$final_round):
          $data .= '<div class="connector connector-'.$connector.'-'.$round.' '.$connector.'-'.$dir.'">';
              if($connector == 'top')
                  $data .=  '<div class="connector-h c-h'.$round.' h-'.$dir.'"></div>';
              $data .= '</div>';
        endif;
        $data .='
            <div class="match-details ">'.$details.'</div>
            <div class="match-details"></div> <!-- spacer -->
          </div>
        </div>';	
      echo $data;
    }

    /**
     * add podium right after final match
     * @return string html
     */
    protected function _add_podium($direction)
    {
        $data = '<div class="podium">
        <div class="podium-place" style="border:0">
            Winners
          </div>
          <div class="podium-place first">
           <i class="fas fa-trophy  trophy-gold"></i> 
           <span class="">1st</span>

           
          </div>
          <div class="podium-place">
            <i class="fas fa-medal trophy-silver"></i> 2nd
          </div>';
          if($this->play_bronze_round):
            $data .= '<div class="podium-place third">
                    <i class="fas fa-award trophy-bronze"></i> 3rd
                    </div>';
          endif;
          $data .= '</div>';
          echo $data;
    }
    /**
     * open round
     * @param int $round
     * @param string $match_no
     * @return match html
     */
    protected function _open_round($round, $match_no)
    {          
        $data = " <div class=\"round round-{$round}\"> ";
        if($this->use_titles):
            $data .= "<div class=\"round-title\">
                        {$this->rounds_title[$round]}
                        <div class=\"round-matches\">{$this->header_match_label} {$match_no}</div>
                      </div>";
        endif;           
      echo $data;
    }

    /**
     * @return html element
     */
    protected function _close_round( )
    {
        echo "</div>";
    }

    /**
     * @return html element
     */
    protected function _open_bracket( )
    {   
        echo '
            <div class="bracket-wrapper">            
                <div class="bracket">';
    }

    /**
     * @return html element
     */
    protected function _close_bracket( )
    {
        echo '
        </div>
        </div>';
    }

    protected function _add_playoffs_title( )
    {
        return
        "<div class=\"bracket playoffs\">{$playoffs}</div>";
    }

}