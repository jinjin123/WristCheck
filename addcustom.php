<?php
use Drupal\paragraphs\Entity\Paragraph;
$database = \Drupal::database();
$a[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Movement',
        'field_value' => 'Manual winding',
 ]); 
$b[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Movement/Caliber',
        'field_value' => '3135',
 ]); 
$c[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Base caliber',
        'field_value' => 'cal.3135',
 ]); 
$d[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Power reserve',
        'field_value' => '48h',
 ]);
$e[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Number of jewels',
        'field_value' => '31',
 ]);
$c1 = array_merge_recursive($a,$b,$c,$d,$e);
$pa[] = Paragraph::create([
     'type' => 'wristcheck_product_custom_parame',
     'field_name'=>'Caliber',
     'field_items' => $c1,
]);

$f[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Bracelet material',
        'field_value' => 'Steel',
 ]);

 $g[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Bracelet color',
        'field_value' => 'Steel',
 ]);

 $h[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Clasp',
        'field_value' => 'Fold clasp',
 ]);

$i[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Clasp material',
        'field_value' => 'Steel',
 ]);

 $c2 = array_merge_recursive($f,$g,$h,$i);

$p1[] = Paragraph::create([
     'type' => 'wristcheck_product_custom_parame',
     'field_name'=>'Bracelet/strap',
     'field_items' => $c2,
]);


$j[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Case material',
        'field_value' => 'Steel',
 ]);

$k[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Case diameter',
        'field_value' => '40mm',
 ]);

 $l[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Water resistance',
        'field_value' => '30ATM',
 ]);

$m[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Bezel material',
        'field_value' => 'Ceramic',
 ]);

 $n[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Crystal',
        'field_value' => 'Sapphire crystal',
 ]);
  $o[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Dial',
        'field_value' => 'Green',
 ]);
  $p[] = Paragraph::create([
        'type' => 'wristcheck_pcpi',
        'field_item_key' => 'Dial numerals',
        'field_value' => 'No numerals',
 ]);

 $c3= array_merge_recursive($j,$k,$l,$m,$n,$o,$p);

$p3[] = Paragraph::create([
     'type' => 'wristcheck_product_custom_parame',
     'field_name'=>'Case',
     'field_items' => $c3,
]);


$p2=array_merge_recursive($pa,$p1,$p3);

$x= [];
for($i=0;$i<=7;$i++){
	$icon = file_get_contents('10809695_s210_v1570202069342.jpg');
	$icon_f = file_save_data($icon, 'public://10809695_s210_v1570202069342_'.$i.'.jpg');
	$sec[] = Paragraph::create([
		'type' => 'wristcheck_product_specification',
		'field_icon' => [
		      'target_id' => $icon_f->id(),
		],
		'field_title' => 'Hour'.$i,
	]);
	 array_push($x,\Drupal\commerce_product\Entity\Product::load(314));
}


$r= $database->select('commerce_product','c')
      ->fields('c',['product_id'])
      ->execute()
      ->fetchAll();

foreach($r as $v){
	$product =  Drupal\commerce_product\Entity\Product::load($v->product_id);
	$product->set('field_extras','Date');
	$product->set('field_others','Quick Set');
	$product->set('field_watch_params',$p2);
	$product->set('field_specifications',$sec);
	$product->set('field_related_model',$x);
	$product->set('field_bezel_material','Streetl');
	$product->set('body','Pre-Owned A. Lange & Sohne Datograph Up/Down (405035) manual-wind watch, features a 41mm platinum case surrounding a black dial on a black alligator strap with a platinum tang buckle. Functions include hours, minutes, small seconds, date, power reserve indicator and flyback chronograph. This watch comes complete with box and papers.We back this watch with a 2-Year WatchBox warranty!This watch will be ready to ship in 5-7 days!');
	$product->set('field_introduction','Pre-Owned A. Lange & Sohne Datograph Up/Down (405035) manual-wind watch, features a 41mm platinum case surrounding a black dial on a black alligator strap with a platinum tang buckle. Functions include hours, minutes, small seconds, date, power reserve indicator and flyback chronograph. This watch comes complete with box and papers.We back this watch with a 2-Year WatchBox warranty!This watch will be ready to ship in 5-7 days!');
	$product->save();
}

