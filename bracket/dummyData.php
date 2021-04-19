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

    function get_dummy_data($rounds){

        $names = count($this->teams) - 1;
        $scor  = count($this->score) - 1;
        $game_data = array();
        
      foreach($rounds as $key => $val):
        unset( $game_data );
        for($i=1; $i<=$val['matches'] ;$i++):
           
            $team1 = rand(0,$names);
            $team2 = rand(0,$names);
            $score1 = rand(0,$scor);
            $score2 = rand(0,$scor);
            $win = ''; $tie1 = '';$tie2 = '';
            if($this->score[$score1] > $this->score[$score2]) { $win = '1'; }
            if($this->score[$score1] < $this->score[$score2]) { $win = '2'; }
            if($this->score[$score1] < $this->score[$score2]) { $win = '2'; }
            if($this->score[$score1] == $this->score[$score2]) { $tie1 = rand(0,5) ;$tie2 = rand(0,5); }

            $game_data[$i] = [
                'team_1'  => $this->teams[$team1],
                'team_2' => $this->teams[$team2],
                'score_1'  => $this->score[$score1],
                'score_2' => $this->score[$score2],
                'details' => '<a href="#">details</a>',
                'winner'  => $win,
                'tie1' => $tie1,
                'tie2' => $tie2
            ];
        endfor;       
       $rounds[$key]['game_data'] = $game_data;
      endforeach;
      return $rounds;

    }
  }
