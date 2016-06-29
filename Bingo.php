<?php
class Bingo
{
  public function getNumbers()
  {
    // creo il sacco di 90 numeri
    $this->numbers = array();
    for($i=1;$i<=90;$i++)
      $this->numbers[] = $i;
    return $this;
  }
  
  public function __construct($cards_number)
  {
    $this->getNumbers()->shuffle();
    $this->createCards($cards_number);
    $this->extractNumbers();
  }
  
  public function shuffle()
  {
    // mischio il sacco 3 volte
    $times = 3;
    while($times--)
      shuffle($this->numbers);
    return $this;
  }
  
  public function get()
  {
    $array = array(
      'cards' => $this->cards,
      'numbers_drawn' => $this->numbers_drawn,
      'winners' => $this->getWinners()
    );
    
    return $array; 
  }
  
  public function getWinners()
  {
    $winners = array(
        'five_row' => array(
            'winners' => $this->winners_five_row,
            'number' => $this->number_five_row
            ),
        'bingo' => array(
            'winners' => $this->winners_bingo,
            'number' => $this->number_bingo
        )
    );
    return $winners;
  }
  
  public function extractNumbers()
  {
    
    while(!$this->number_five_row) // estraggo fino a quando qualcuno non ha fatto cinquina
    {
      $number_drawn = array_shift($this->numbers); // tolgo il numero dal sacco
      $this->numbers_drawn[] = $number_drawn; // lo metto nei numeri estratti
      foreach($this->cards as $key => $card) // controllo le cartelle
        foreach($card as $row) // controllo le righe
          if($this->controlFiveRow($row))
          {
            $this->winners_five_row[] = $key; // ho fatto cinquina e aggiungo i giocatori
            $this->number_five_row = $number_drawn;
          }
    }
    
    while(!$this->number_bingo) // estraggo fino a quando qualcuno non ha fatto bingo
    {  
      $number_drawn = array_shift($this->numbers); // tolgo il numero dal sacco
      $this->numbers_drawn[] = $number_drawn; // lo metto nei numeri estratti
      foreach($this->cards as $key => $card) // controllo le cartelle
        if($this->controlBingo($card))
        {
          $this->winners_bingo[] = $key; // ho fatto bingo e aggiungo i giocatori
          $this->number_bingo = $number_drawn;
        }
    }
    
  }
  
  public function createCards($cards_number)
  {
    for($i=1;$i<=$cards_number;$i++)
      $this->cards[$i] = $this->createCard();
    return $this;
  }
  
  public function createCard()
  {
    // inizio a creare la cartella
    $columns = array(1,2,3,4,5,6,7,8,9); // ha 9 colonne
    $numeri_usciti = array();
    
    for($row=1;$row<=3;$row++)
    {
      $card[$row] = array();
      shuffle($columns); // mischio le colonne
      if($row == 3 && count($colonne_uscite) < 9) // se è l'ultima riga e ancora non sono uscite tutte le colonne
        foreach($columns as $column)
          if(!in_array($column, $colonne_uscite)) // creo i numeri nelle colonne che non sono uscite
          {
            $number = $this->getNumber($column, $numeri_usciti);
            $card[$row][$column] = $number;
            $numeri_usciti[] = $number;
          }
      
      foreach($columns as $column)
      {
        if(count($card[$row]) == 5) break; // se ho già 5 colonne nella riga mi fermo
        // creo il numero che va nella colonna e lo posiziono
        $number = $this->getNumber($column, $numeri_usciti); 
        if(!isset($card[$row][$column]))  $card[$row][$column] = $number;
        $numeri_usciti[] = $number;
        if(!in_array($column, $colonne_uscite)) $colonne_uscite[] = $column;
      }
      
      // ordino la riga in ordine crescente
      ksort($card[$row]);
    }
    
    return $card;
  }
  
  public function getNumber($column, $numeri_usciti)
  {
    if($column == 1)
      $range = array(1, 9);
    elseif($column == 9)
      $range = array(80, 90);
    else
    {
      $decina = $column-1;
      $range_start = (int)$decina."0";
      $range = array($range_start, $range_start+9);
    }
    while(1)
    {
      $number = rand($range[0], $range[1]);
      if(!in_array($number, $numeri_usciti))
        break;
    }
    return $number;
  }
  
  public function controlFiveRow($row)
  {
    // controllo se ho fatto cinquina nella riga
    $count = 0;
    
    foreach($this->numbers_drawn as $number) // da tutti i numeri fin'ora estratti
      if(in_array($number, $row)) // se il numero è estratto è nella riga incremento di 1
        $count++; 
      
    if($count == 5) // se il count è arrivato a 5 ho fatto cinquina
      return true;
    
    return false;
  }
  
  public function controlBingo($card)
  {
    // per fare bingo mi basta aver fatto cinquina in tutte e 3 le righe
    foreach($card as $row)
    {
      if(!$this->controlFiveRow($row))
        return false;
    }
    return true;
  }
  
}
?>