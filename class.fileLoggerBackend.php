<?php
class fileLoggerBackend{
  public function __construct($url) {
    Config::addConfig('LOGGER_FILE', 'log/prova.log');
    Config::addConfig('LOGGER_LEVEL', Write::INFO);
    $log = Write::getInstance();
    $log->logMessage('Guardo el mensaje', Write::CRITICAL, "Mensaje guardado correctamente");
    echo "Envio de campos de mensaje. <br>";
  }

}
class Write {
  private $hLogFile;
  private $logLevel;

  const DEBUG     = 100;
  const INFO      = 75;
  const NOTICE    = 50;
  const WARNING   = 25;
  const ERROR     = 10;
  const CRITICAL  = 5;
  
  private function __construct() {

    $cfg = Config::getConfig();  

    $this->logLevel = isset($cfg['LOGGER_LEVEL']) ? 
    $cfg['LOGGER_LEVEL'] : 
    Write::INFO;

    if(! ( isset($cfg['LOGGER_FILE']) && strlen($cfg['LOGGER_FILE'])) ) {
      throw new Exception('No log file path was specified ' .
      'in the system configuration.');
    }

    $logFilePath = $cfg['LOGGER_FILE'];


    $this->hLogFile = @fopen($logFilePath, 'a+');

    if(! is_resource($this->hLogFile)) {
      throw new Exception("The specified log file $logFilePath " .
      'could not be opened or created for ' .
      'writing.  Check file permissions.');
    }

  }

  public function __destruct() {
    if(is_resource($this->hLogFile)) {
      fclose($this->hLogFile);
    }
  }

  public static function getInstance() {

    static $objLog;

    if(!isset($objLog)) {
      $objLog = new Write();
    }

    return $objLog;
  }

  public function logMessage($msg, $logLevel = Write::INFO, $module = null) {

    if($logLevel > $this->logLevel) {
      return;
    }

    /* If you haven't specifed your timezone using the 
       date.timezone value in php.ini, be sure to include
       a line like the following.  This can be omitted otherwise.
    */
    date_default_timezone_set('Europe/Madrid');

    $time = strftime('%x %X', time());
    $msg = str_replace("\t", '    ', $msg);
    $msg = str_replace("\n", ' ', $msg);

    $strLogLevel = $this->levelToString($logLevel);

    if(isset($module)) {
      $module = str_replace("\t", '    ', $module);
      $module = str_replace("\n", ' ', $module);
    }

    $logLine = "$time\t$strLogLevel\t$msg\t$module\r\n";
    fwrite($this->hLogFile, $logLine);
    echo "Mensaje guardado correctamente. <br>";
  }

  public static function levelToString($logLevel) {
    switch ($logLevel) {
      case Write::DEBUG:
      return 'Write::DEBUG';
      break;
      case Write::INFO:
      return 'Write::INFO';
      break;
      case Write::NOTICE:
      return 'Write::NOTICE';
      break;
      case Write::WARNING:
      return 'Write::WARNING';
      break;
      case Write::ERROR:
      return 'Write::ERROR';
      break;
      case Write::CRITICAL:
      return 'Write::CRITICAL';
      break;
      default:
      return '[unknown]';
    }
  }
}

  ?>