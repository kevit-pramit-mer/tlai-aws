<?php
/*echo $cmd = '/bin/tiff2pdf -o /media/admin/fax/18001919/02b613c7-bda0-4a4e-8eca-34608e55067e.pdf /media/admin/fax/18001919/02b613c7-bda0-4a4e-8eca-34608e55067e.tif > /dev/null 2>&1;';

echo $process = system($cmd);
*/

//phpinfo();

$images = new Imagick('/non_critical_media/fax-data/temp_files/faxrx-1-1594215622454833.tif');

    $images->setImageFormat("pdf"); 


$images->writeImages('/non_critical_media/fax-data/temp_files/faxrx-1-1594215622454833.pdf', true);


?>
