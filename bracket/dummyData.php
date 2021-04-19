<?php

class dummyData {

protected $teams = [ 
                'Badgers',
                'Bengals',
                'Royals',
                'Chili Peppers' ,
                'Cereal Killers',
                'Abusement Park',
                'Aztecs',
                'Red Dragons',
                'The Surge',
                'Demon Deacons',
                'Fast & The Furious',
                'Big Blues',
                'Bisons',
                'Fusion',
                'Golden Knights',
                'Bandits',
                'Bantams',
                'Phantoms',
                'Red Foxes',
                'Brigade',
                'Blue Typhoons',
                'Flying Squirrels',
                'Celtic Ladies',
                'Cheetahs',
                'She Devils',
                'Majestics',
                'Lady Cougars',
                'She Got Game',
                'Black Antelopes',
                'Black Stars',
                'Dazzle',
                'Amigos',
                'Green Wave',
                'Boilermakers',
                'Bombers',
                'Colonels',
                'Chippewas',
                'Catamounts',
                'Crimson Tide',
                'Orange Crush',
                'Crushers',
                'Dancing Divas',
                'Demolition Day',
                'Musketeers'
            ];
    protected $score = [
                '0','1','2','3','4','5', '6', '7', '8', '9', '10', '11', '12', '13',
                '14', '15', '16', '17', '18', '19',  '20'  ];


    function generate_dummy_data($players, $p_bronze = false)
    {   
        $rounds = $this->calculate_rounds($players,$p_bronze);
       
        
        $names = count($this->teams) - 1;
        $scor  = count($this->score) - 1;
        $game_data = array();
        $tmp = $rounds;
        foreach($tmp as $key => $val){
            unset( $game_data );
            for($i=0;$i<$val['matches'] ;$i++):
               
                $team1 = rand(0,$names);
                $team2 = rand(0,$names);
                $score1 = rand(0,$scor);
                $score2 = rand(0,$scor);
                $win = ''; $tie = '';
                if($this->score[$score1] > $this->score[$score2]) { $win = '1'; }
                if($this->score[$score1] < $this->score[$score2]) { $win = '2'; }
                if($this->score[$score1] < $this->score[$score2]) { $win = '2'; }
                if($this->score[$score1] == $this->score[$score2]) { $win = rand(1,2); $tie = '5-4 pk'; }
    
                $game_data[] = [
                    'team_1'  => $this->teams[$team1],
                    'team_2' => $this->teams[$team2],
                    'score_1'  => $this->score[$score1],
                    'score_2' => $this->score[$score2],
                    'details' => '<a href="#">link</a>',
                    'winner'  => $win,
                    'tiebreaker' => $tie
                ];
            endfor;
           
           $rounds[$key]['game_data'] = $game_data;
        }

        return $rounds;
    }

    protected function calculate_rounds($total_players,$play_bronze)
    {	
        if( $total_players % 16 > 0):
          while($total_players % 16 == 0)
            $total_players++;
        endif;
    
    //  $rounds = [];
      $n = null;	
      $key = -1;
        // max 256 players .. to allow more increase the $i<8
      for($i=0;$i<8;$i++){
        $n = $n == null ?  $total_players : $n;
        $n = $n / 2;
        $key++;
        if($n == 1):
          if($play_bronze){
            $rounds[$key ]['matches'] = 2;
            $rounds[ $key]['game_data'] = array();
          }
          else{
            $rounds[$key]['matches'] = $n;
            $rounds[$key ]['game_data'] = array();
          }
          break;
        endif;
        $rounds[$key ]['matches']= $n;
        $rounds[$key ]['game_data'] = array();
        
        }
      return $rounds;
    }
}