<?php
use Drupal\taxonomy\Entity\Term;
$file = fopen("ser.csv","r");
$i = 0;
while($data = fgetcsv($file,1000000,";"))
{
        $i++;
        if($i==1)
        {
            continue;//过滤表头
        }
        if($data[0]!='')
        {
		$des = array(
			"Datejust" => "The Datejust is the first-ever self-winding chronometer with a date window. The Datejust is available in varying sizes (28-41mm), materials (steel, two-tone, solid gold) and dial colours, many of which have become modern-day staples of collections",
			"Oyster Perpetual" => "A direct descendant of the original 1927 Rolex Oyster, the Rolex Oyster Perpetual is",
			"Sky-Dweller" => "The Rolex Sky-Dweller is the most complicated Rolex watch ever manufactured.",
			"Day-Date" => "Day-Date	The Rolex Day-Date, presented in 1956, was the first wristwatch to display the day (spelled in full) and the date at the same time. The Day-Date has been worn by some of the most prominent figures in history, including high-profile political figures, movie stars and more and has long been regarded as the standard of luxury and affluence",
			"Cellini" => "The Cellini is Rolex's only collection that celebrates classical watchmaking and a more traditional sense of timeless design.",
			"Lady-Datejust" => "The Lady Datejust was launched as the female-friend counterpart to the Datejust. The Lady Datejust is available in varying sizes (28-41mm), materials (steel, two-tone, solid gold) and dial colours, many of which have become modern-day staples of collections",
			"Pearlmaster" => "The Rolex Pearlmaster was launched in 1992 as part of the Oyster Perpetual series to feature watches made from the precious metals and embellished with gemstones.",
			"Yachtmaster"=>"The Rolex Yacht-Master was first introduced in 1992 as an addition to the professional series aiming to target the world of sailing and yachting.",
			"GMT-Master II"=> "The Rolex GMT-Master II is a direct descendant of the Rolex GMT-Master, which was originally designed in 1952 in conjunction with PAN-AM (Pan American Airways) to be used by their pilots and navigators on long haul flights. The first GMT Master release featured a bi-directional rotating bezel with a sharp blue and red colour to match the PAN-AM colourway; this release is now more popularly known as the 'Pepsi' bezel and is one of the most coveted rolex variations in the market today",
			"Explorer" => "The Rolex Explorer series was developed for explorers who would navigate through the most trecherous terrains on earth and was released as as an homage to some of the world's most daring explorers back in the day. The Explorer's predecessor (a heavily modified rolex) was famoulsy worn by Tenzing Norway and Sir Edmund Hillary in their Everst Summit in 1953.",
			"Milgauss" => "The Rolex Milgauss was introduced in 1956 to meet the work demands of the scientists and technicians who were working in close proximity to electromagnetic fields. The Milguass is the first watch to be able to function properly even under extreme circumstances (magnetic fields of up to 1000 gauss) and has therefore been worn by some of the most prominent scientists, and most well-known by the scientists at CERN (European Organization for Nuclear Research)",
			"Air King" => "The Air King was created in 1958 to honor the Royal Air Force pilots after the Battle of Britain. The Air King remained in production until 2014, when it was removed from production, only to be reintroduced in 2016 in a larger, 40mm case.",
			"Submariner" => "The Rolex Submariner is one of the most celebrated diver's watch of the last century. Having also appeared in various early James Bond movies, the Submariner has also solidified its cultural significance amoungst collectors of all stages.	",
			"Cosmograph Daytona" => "Created by Rolex in 1963, the Cosmograph Daytona pays homage to Daytona (Florida), where racing culture was prominent in the 20th century. The Cosmograph Daytona served as a canvas for some of Rolex's most coveted pieces, such as the Daytona 'Paul Newman', which ranks as one of the most expensive watches to ever be sold at auction in December 2019",
		);

		$categories_vocabulary = 'watch_series_categories';
		if($data[0] !=''){
  				$term = Term::create(array(
    					'parent' => array(),
					'name' => $data[0], 
					'description'=> $des[$data[0]] ,
    					'vid' => $categories_vocabulary,
  				))->save();
		}

        }
}

fclose($file);
?>
