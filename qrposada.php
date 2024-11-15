<?php
    include('vendor/phpqrcode/qrlib.php');
        
    
  
   

    $row = 1;
    if (($handle = fopen("C:\Users\sergio.gonzalezr\Downloads\Libro2.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 20000, ",")) !== FALSE) {
        echo $data[0]. " - " . $data[1] . " - (" . $data[2].")";
        echo "<br>";
        $row++;

         ob_start("callback");
    
        // end of processing here
        $debugLog = ob_get_contents();
        ob_end_clean();
        
        // outputs image directly into browser, as PNG stream
        QRcode::png($data[0]. " - " . $data[1] . " - (" . $data[2].")", "C:\Users\sergio.gonzalezr\Downloads\codigosqr\\".$data[0]."-".$data[1].".png");

        
      }
      fclose($handle);
    }

?>