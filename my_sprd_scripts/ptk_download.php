<?php
  
    // Initialize a file URL to the variable
    $url = 
    'https://ptk-svarka.ru/personal/export/prices.xlsx';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = 'ptk_'.basename($url);
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents($url)))
    {
        echo "File downloaded successfully";
        print "<br>".$date;
    }
    else
    {
        echo "File downloading failed.";
    }
?>