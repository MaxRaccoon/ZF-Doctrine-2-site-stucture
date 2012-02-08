<?php
use Square\Entity\Country;

// 'batch-config.php' parses application.ini and instantiates Bisna\Doctrine\Container as $container.
include "batch-config.php";

$em = $container->getEntityManager();

// countries.xml is from http://www.iso.org/iso/iso_3166-1_en_xml.zip
$xml = simplexml_load_file('./countries.xml');

$batchSize = 20;
$count = 0;

try {

   foreach ($xml->children() as $object) {
       
            $country = new Country( array( 'name' => (string) $object->{'ISO_3166-1_Country_name'},
                               'code' => (string) $object->{'ISO_3166-1_Alpha-2_Code_element'})
                               );

	    $em->persist($country);

		if ($count % $batchSize == 0) {

            $em->flush();
            $em->clear(); // Detaches all objects from Doctrine!
     }	

    	$count++;
   }

} catch(Exception $e) {

    echo $e->getMessage();
    return;
}
?>
