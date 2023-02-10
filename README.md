# PHPTournamentBracketsGenerator

<h1 align="center">
Tournament bracket generator using PHP CSS HTML
</h1>

<h4 align="center">A class to generate and work with single elimination bracket lrt and rlt, in a easy way</h4>

## Features

- Creates  tournament bracket with minimum of 4 and max of 256 players
- Add round label
- Add match link details
- Generates ltr direction
- Generates rtl direction
- Generates empty brackets
- Generates bracket with match results
- Generates bronze match
- allows to feed extra time results 

##  Usage

```php
require_once('bracket/arpBrackets.php'); 
// add dummy data to generates some dummy data its not need in productive system
require_once('bracket/dummyData.php');  

  // for testing purpose
  $players = @$_GET['players'];     // if players is supplier in query string, we use it 
  if($players <= 0)  $players = 32; // or set 32 by default number of players
  $bronze =  @$_GET['bronze'];      // set bronze=true to generates bronze round            
  
  $dummy = new dummyData();  
  $brackets  = new arpBrackets(  );  
  
  
  // set number of players or u just can pass it to constructor
  $brackets->set_players( $players ); 

  // enable/disable bronze round
  $brackets->set_bronze_round($bronze); 

  // display podium after last match
  $brackets->show_podium(false);

   // enable css calculation on the fly
   // if true css will be calculate and add to html body, if false will be need to add css on your own
  $brackets->css_on_the_fly(true);

  // set header match label  - output Games 32, Games 16..... and so on
  $brackets->set_match_label('Games'); 

    // set header std round label + round number eg. Round 1 , Round 2 and so on    
  $brackets->set_round_label('Round'); 
    
    // if you prefer you can set a specific title for each round passing it to array
     // $array['1'] = 'First round'...
     // the array counting need to start with 1
  // $brackets->set_round_title_array($array);

    // enable/disable round title to be shown
    $brackets->set_titles(false); 
  
    // calculate number of rounds
    $brackets->calculate_rounds();
    
    // generates some dummy data
    $game_data = $dummy->get_dummy_data( $brackets->get_rounds() );
    
     // set LTR direction, RTL is the default diretion
  // $brackets->set_direction($brackets::LEFT2RIGHT);
  
    // build  single elimination bracket, to build an empty bracket do not pass $game_data array
    $brackets->draw_single_elimination( $game_data );  
    
    ![brackets](https://user-images.githubusercontent.com/48767004/217975377-5382d8d0-2afb-4856-8cd4-e999c5529548.JPG)


