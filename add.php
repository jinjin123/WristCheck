<?php
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;
$store = \Drupal\commerce_store\Entity\Store::load(1);
$file = fopen("clearnew.csv","r");
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
            $price = new \Drupal\commerce_price\Price($data[3], 'USD');
            $variation = \Drupal\commerce_product\Entity\ProductVariation::create([
              'type' => 'watch', 
              'sku' => $data[5], 
              'status' => 1,
              'price' => $price,
              'title' => $data[0],
              'attribute_color' => 'red', 
            ]);

	   for($i=0;$i<=4;$i++){
	   	$bdata = file_get_contents('big.jpg');
	   	$bfile = file_save_data($idata, 'public://big'.$i.'.jpg');
	  	$icon = file_get_contents('10809695_s210_v1570202069342.jpg');
	   	$icon_f = file_save_data($icon, 'public://10809695_s210_v1570202069342_'.$i.'.jpg');
		$childpa[] = Paragraph::create([
			'type' => 'wristcheck_pcpi',
		   	'field_item_key' => 'Movement',
		   	'field_value' => 'Manual winding',
		]);
		$sec[] = Paragraph::create([
			'type' => 'wristcheck_product_specification',
			'field_icon' => [
    				'target_id' => $icon_f->id(),
			],
			'field_title' => 'Hour',
	 	]);
		$imgid[] = [
    			'target_id' => $bfile->id(),
		];
	   }

	   $pa = Paragraph::create(['type' => 'wristcheck_product_custom_parame',]);
	   $pa->set("field_name",'Bracelet/strap'); 
	   $pa->set('field_items',$childpa); 
	   $pa->save();
	   $idata = file_get_contents('watch3.jpg');
	   $ifile = file_save_data($idata, 'public://watch3.jpg');

	   $bdata = file_get_contents('big.jpg');
	   $bfile = file_save_data($idata, 'public://big.jpg');
            /*$t = explode(",",$data[2]);
	    $tmp=array();
	    foreach($t as $value){
                       $b = str_replace("'","",$value);
                        $c = str_replace("u","",$b);
                        $d= str_replace ("[","",$c);
                        $e= str_replace ("]","",$d);
                        array_push($tmp,trim($e));
	    };*/

	   $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['name' => $data[0]]);
           $product = \Drupal\commerce_product\Entity\Product::create([
              'stores' => [$store],
              'variations' => [$variation],
              'uid' => 1,
              'type' => 'watch', 
              'title' => $data[4],
	      'field_subname' => $data[4],
              'field_ask_price'=>$price,
              'field_bezel_material ' => !empty(mb_substr($data[17],0,20)) ? mb_substr($data[17],0,20) : '13mm' ,
              'field_case_back' => !empty(mb_substr($data[19],0,30)) ? mb_substr($data[19],0,30) : '14mm',
              'field_case_back_width' => !empty(mb_substr($data[21],0,20)) ? mb_substr($data[21],0,20) : '15mm',
              'field_case_band_color' => !empty(mb_substr($data[25],0,20)) ? mb_substr($data[25],0,20) : 'black',
              'field_case_band_length' => !empty(mb_substr($data[26],0,20)) ? mb_substr($data[26],0,20) :'13mm',
              'field_case_band_material' => !empty(mb_substr($data[23],0,20)) ? mb_substr($data[23],0,20) : 'street',
              'field_case_band_width' => !empty(mb_substr($data[24],0,20)) ? mb_substr($data[24],0,20) : '19mm',
              'field_case_diameter' => !empty(mb_substr($data[14],0,20))? mb_substr($data[14],0,20) : '10mm',
              'field_case_face' => !empty(mb_substr($data[18],0,20)) ? mb_substr($data[18],0,20) : 'Street',
              'field_case_number' => !empty(mb_substr($data[22],0,20)) ? mb_substr($data[22],0,20) :'10213',
              'field_case_shape' => !empty(mb_substr($data[20],0,20)) ?  mb_substr($data[20],0,20) : 'shape',
              'field_case_thickness' => !empty(mb_substr($data[15],0,20)) ? mb_substr($data[15],0,20) : '13mm',
              'field_case_weight' => !empty(mb_substr($data[32],0,20)) ? mb_substr($data[32],0,20): '10mm',
              'field_cate' => $term,
	      //'field_cate_img' => [
    	//	'target_id' => $ifile->id(),
	  //     ] ,
              'field_clasp' => !empty(mb_substr($data[27],0,20)) ? mb_substr($data[27],0,20) :'clasp',
              'field_clasp_material' => !empty(mb_substr($data[23],0,20)) ? mb_substr($data[23],0,20) : 'street',
              'field_crust_material' => !empty(mb_substr($data[9],0,20)) ? mb_substr($data[9],0,20): 'street',
	      //'field_introduction' => !empty(mb_substr($data[31],0,100)) ? mb_substr($data[31],0,20) : 'The watch is very goodlThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very good',
	      'field_introduction' =>  'The watch is very goodlThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very good',
              //'body' => !empty(mb_substr($data[31],0,100)) ? mb_substr($data[31],0,20) : 'The watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very good',
              'body' =>  'The watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very good',
	       'field_extras' => !empty(mb_substr($data[29],0,20)) ? mb_substr($data[29],0,20) : 'The watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very goodThe watch is very good',
               'field_manufacturer' =>!empty(mb_substr($data[11],0,20))? mb_substr($data[11],0,20) : 'CHina',
               'field_others' => !empty(mb_substr($data[30],0,20)) ? mb_substr($data[30],0,20): 'box',
               'field_power_time' => !empty(mb_substr($data[12],0,20))  ? mb_substr($data[12],0,20) : '44h',
               'field_watch_sex' => !empty(mb_substr($data[10],0,20))  ? mb_substr($data[10],0,20)  : 'Men',
               'field_shock' => !empty(mb_substr($data[13],0,20)) ?  mb_substr($data[13],0,20)  : 'Street',
               'field_sku' => !empty(mb_substr($data[5],0,20))? mb_substr($data[5],0,20) : '123123',
	       'field_ref_number' => !empty(mb_substr($data[5],0,20)) ? mb_substr($data[5],0,20) :'123123',
               'field_watch_core' => !empty(mb_substr($data[7],0,20)) ? mb_substr($data[7],0,20): 'Automatic',
               'field_watch_core_num' => !empty(mb_substr($data[8],0,20)) ? mb_substr($data[8],0,20) : '12',
               'field_watch_style' => !empty(mb_substr($data[6],0,20)) ? mb_substr($data[6],0,20): 'red',
               'field_water_resistance' => !empty(mb_substr($data[16],0,20)) ? mb_substr($data[16],0,20) : '300m',
               'field_year' => !empty(mb_substr($data[33],0,20)) ? mb_substr($data[33],0,20) : '2016',
	       'field_detailimg' =>  $imgid,
	       'field_verified_dealer'=> '4',
	      'field_condition' => 'New',
	      'field_scope_of_delivery' => 'box',
	      'field_watch_params'=>$pa,
	      'field_model_images'=>  [
    		'target_id' => $ifile->id(),
  	       ],
	      'field_related_brand' => \Drupal\node\Entity\Node::load('426'),
	      'field_related_model'=> \Drupal\commerce_product\Entity\Product::load(314),
	      'field_model_tags' => \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['name' =>'JASKETS']),
	      'field_specifications' => $sec ,
            ]);
	    $product->save();
	    $sec=[];
	    $imgid=[];
	    $childpa=[];
        }
}

fclose($file);
?>
