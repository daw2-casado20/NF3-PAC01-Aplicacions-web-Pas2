<?php
  

class Logger {
  public $URL;
  
 

  //Log Levels.  The higher the number, the less severe the message
  //Gaps are left in the numbering to allow for other levels
  //to be added later
  public $DEBUG     = 100;
  public $INFO      = 75;
  public $NOTICE    = 50;
  public $WARNING   = 25;
  public $ERROR     = 10;
  public $CRITICAL  = 5;
  
  //Note: private constructor.  Class uses the singleton pattern
   public function __construct($url) {
    $URL = $url;
    $urlData = parse_url($URL); 
    echo 'Se abre la clase del archivo '.'class.'.$urlData['scheme'].'LoggerBackend.php'.' <br>';
    if(! isset($urlData['scheme'])) { 
      throw new Exception("Invalid log connection string $connectionString");
    }
    
    include_once('class.'.$urlData['scheme'].'LoggerBackend.php'); 

    $className = $urlData['scheme'].'LoggerBackend'; 

    echo 'Se abre la clase '.$className.' <br>';

    if(!class_exists($className)) { 
      throw new Exception('No logging backend available for '.$urlData['scheme']); 
    } 
    $objBack = new $className($urlData); 

    
  }
}
?>