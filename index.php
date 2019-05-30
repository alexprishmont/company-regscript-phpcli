<?php
  $GLOBALS['database'] = NULL;
  
  require_once("core/classes/database.class.php");
  require_once("core/classes/company.class.php");
  require_once("core/classes/menu.class.php");

  $menu = new Menu();

  // Check if arguments count is valid
  if ($argc > 2) {
    echo "Your entered arguments are wrong!\n";
    echo "Usage: ".$argv[0]." or ".$argv[0]." [CSV file location]\n";
  }
  else if ($argc <= 1 || $argc >= 0) {
    // Lets try connect to database
    try {
      $GLOBALS['database'] = new Database();
    } catch (PDOException $e) {
      echo $e->getmessage();
    }
    
    if (isset($argv[1])) { // if csv file location exists in arguments, we start importing it.
      $path_parts = pathinfo($argv[1]);
      if ($path_parts['extension'] == 'csv') {
        $file = fopen($argv[1], "r");
        if ($file) {
          while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
            try {
              $GLOBALS['database']->query(
                "INSERT INTO `companies` (name, registration_code, email, phone, comment) VALUES (?,?,?,?,?)",
                [$data[0], $data[1], $data[2], $data[3], $data[4]]
              );
            } catch (PDOException $e) {
              echo $e->getmessage();
              exit;
            }
          }
          fclose($file);
          echo "CSV file successfully imported to database.";
        }
        else {
          echo "Cannot open file.\n";
          exit;
        }
      }
      else {
        echo "Extension of the given file should be .csv! Other files we cannot import.\n";
        exit;
      }
    }
    else {
      echo "Hello!\n"; 
      echo "Choose options from menu below: \n";

      $menu->show();
      $menu->choose();
    }
  }
?>