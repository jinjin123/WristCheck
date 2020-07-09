<?php



use Drupal\Component\Utility\Html;

use Drupal\Core\Form\FormStateInterface;

use Drupal\system\Form\ThemeSettingsForm;

use Drupal\file\Entity\File;

use Drupal\Core\Url;



function wristcheck_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $theme_file = drupal_get_path('theme', 'wristcheck') . '/theme-settings.php';

    $build_info = $form_state->getBuildInfo();

    if (!in_array($theme_file, $build_info['files'])) {

        $build_info['files'][] = $theme_file;

    }

    $form_state->setBuildInfo($build_info);



    $form['#submit'][] = 'wristcheck_theme_settings_form_submit';





        $form['settings'] = array(

        '#type' => 'details',

        '#title' => t('Theme settings'),

        '#open' => TRUE,

        '#attached' => array(

          'library' =>  array(

            'wristcheck/theme-color-lib'

          ),

        ),

        

    );





    $form['settings']['general_setting'] = array(

        '#type' => 'details',

        '#title' => t('General Settings'),

        '#open' => FALSE,

    );



    $form['settings']['general_setting']['general_setting_tracking_code'] = array(

        '#type' => 'textarea',

        '#title' => t('Tracking Code'),

        '#default_value' => theme_get_setting('general_setting_tracking_code', 'wristcheck'),

    );



    $form['settings']['general_setting']['page_header_background'] = array(

    '#type' => 'details',

    '#title' => t('Background'),

    '#open' => FALSE,

      );  

    
     $form['settings']['general_setting']['layout_type'] = array(

         '#type' => 'select',

        '#title' => t('Layout Type'),

        '#options' => array(

            'type1' => t('Page title 1'),

            'type2' => t('Page title 2'),

            'type3' => t('Page title 3'),

            'type4' => t('Page title 4'),

            'type5' => t('Page title 5'),

            'type6' => t('Page title 6'),

            'type7' => t('Page title 7'),

             'type8' => t('Page title 8'),

             'type9' => t('Page title 9'),
         ),

         '#required' => true,

        '#default_value' => theme_get_setting('layout_type', 'wristcheck'),

     );
    

      $form['settings']['general_setting']['page_header_background']['page_header_background_image'] = array(

        '#type' => 'managed_file',

        '#title' => t('Upload image'),



        '#upload_location' => file_default_scheme() . '://background_images',

        '#default_value' => theme_get_setting('page_header_background_image','wristcheck'), 

        '#upload_validators' => array(

          'file_validate_extensions' => array('gif png jpg jpeg apng svg'),

          //'file_validate_image_resolution' => array('960x400','430x400')

        ),

        '#description' => t('If you don\'t jave direct access to the server, use this field to upload your background image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),

        //'#element_validate' => array('save_image_upload'),

      );

    $form['settings']['logo']  = array(

        '#type' => 'details' , 

        '#title' => t('Logo settings'),

        '#open' => False,

    );  

     $form['settings']['logo']['logo_white_image'] = array(

        '#type' => 'managed_file',

        '#title' => t('Upload Logo White Image'),



        '#upload_location' => file_default_scheme() . '://background_images',

        '#default_value' => theme_get_setting('logo_white_image','wristcheck'), 

        '#upload_validators' => array(

          'file_validate_extensions' => array('gif png jpg jpeg apng svg'),

        ),

      );


     $form['settings']['header'] = array(

        '#type' => 'details',

        '#title' => t('Header settings'),

        '#open' => FALSE,

    );

// Header settings

    $form['settings']['header'] = array(

        '#type' => 'details',

        '#title' => t('Header settings'),

        '#open' => FALSE,

    );

     $form['settings']['header']['header_layout'] = array(

        '#type' => 'select',

        '#title' => t('Top header bg'),

        '#options' => array(
            'header1' => t('Header 1'),
            'header2' => t('Header 2'),
            'header3' => t('Header 3'),
            'header4' => t('Header 4'),
            'header5' => t('Header 5'),
        ),

        '#required' => true,

        '#default_value' => theme_get_setting('header_layout', 'wristcheck'),

    );

// End Header

// Footer settings

    $form['settings']['footer'] = array(

        '#type' => 'details',

        '#title' => t('Footer settings'),

        '#open' => FALSE,

    );

     $form['settings']['footer']['footer_layout'] = array(

        '#type' => 'select',

        '#title' => t('Bottom footer bg'),

        '#options' => array(

            'footer1' => t('Footer 1'),

            'footer2' => t('Footer 2'),

            'footer3' => t('Footer 3'),

            'footer4' => t('Footer 4'),

            'footer5' => t('Footer 5'),

            'footer6' => t('Footer 6'),

            'footer7' => t('Footer 7'),

            'footer8' => t('Footer 8'),

            'footer9' => t('Footer 9'),

            'footer10' => t('Footer 10'),

            'footer11' => t('Footer 11'),

            'footer12' => t('Footer 12'),

        ),

        '#required' => true,

        '#default_value' => theme_get_setting('footer_layout', 'wristcheck'),

    );

// End Footer

// Blog

    $form['settings']['blog'] = array(

        '#type' => 'details',

        '#title' => t('Blog settings'),

        '#open' => FALSE,

    );

    
    $form['settings']['blog']['blog_header_background_image'] = array(

        '#type' => 'managed_file',

        '#title' => t('Upload image'),



        '#upload_location' => file_default_scheme() . '://background_images',

        '#default_value' => theme_get_setting('blog_header_background_image','wristcheck'), 

        '#upload_validators' => array(

          'file_validate_extensions' => array('gif png jpg jpeg apng svg'),

          //'file_validate_image_resolution' => array('960x400','430x400')

        ),

        '#description' => t('If you don\'t jave direct access to the server, use this field to upload your background image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),

        //'#element_validate' => array('save_image_upload'),

      );

    $form['settings']['shop']['shop_sidebar'] = array(

    '#type' => 'select',

    '#title' => t('Shop Sidebar'),

    '#options' => array(

        'left' => t('Left Sidebar'),

        'right' => t('Right Sidebar'),

        'none' => t('None'),

    ),

    // '#required' => true,

    '#default_value' => theme_get_setting('shop_sidebar', 'wristcheck'),

    );

   $form['settings']['blog']['blog_sidebar'] = array(

    '#type' => 'select',

    '#title' => t('Blog Sidebar'),

    '#options' => array(

        'left' => t('Left Sidebar'),

        'right' => t('Right Sidebar'),

    ),

    // '#required' => true,

    '#default_value' => theme_get_setting('blog_sidebar', 'wristcheck'),

    );

     $form['settings']['blog']['blog_layout'] = array(

        '#type' => 'select',

        '#title' => t('Blog Layout Style'),

        '#options' => array(

            'grid-1-col' => t('Grid 1 col'),   

            'grid-2-cols' => t('Grid 2 cols'),   

            'grid-3-cols' => t('Grid 3 cols'),

            'grid-4-cols' => t('Grid 4 cols'),        

            'masonary' => t('Masonary'),  
    ),

        // '#required' => true,

        '#default_value' => theme_get_setting('blog_layout', 'wristcheck'),

    );

    

    $form['settings']['blog']['blog_single'] = array(

        '#type' => 'details',

        '#title' => t('Blog single settings'),

        '#open' => FALSE,

    );

   

    $form['settings']['blog']['blog_single']['blog_single_style'] = array(

        '#type' => 'select',

        '#title' => t('Default Style '),

        '#options'  => array(

            'creative' => t('Creative'),        

            'classic' => t('Classic'),

        ),

        '#default_value' => theme_get_setting('blog_single_style', 'wristcheck'),

    );

// end Blog   



// custom css

    $form['settings']['custom_css'] = array(

        '#type' => 'details',

        '#title' => t('Custom CSS'),

        '#open' => FALSE,

    );



    $form['settings']['custom_css']['custom_css'] = array(

        '#type' => 'textarea',

        '#title' => t('Custom CSS'),

        '#default_value' => theme_get_setting('custom_css', 'wristcheck'),

        '#description' => t('<strong>Example:</strong><br/>h1 { font-family: \'Metrophobic\', Arial, serif; font-weight: 400; }')

    );

// end Css

//shop

    

// end shop

}



function wristcheck_theme_settings_form_submit(&$form, FormStateInterface $form_state) {

    $account = \Drupal::currentUser();

    $values = $form_state->getValues();

    $bg[0] = $values['page_header_background_image']; 

    $bg[1] = $values['logo_white_image'];

    $bg[2] = $values['blog_header_background_image'];


    // $bg[2] = $values['background_image']; 

    $count = count($bg);

    for ($i=0; $i < $count; $i++) {



    if (isset($bg[$i]) && !empty($bg[$i])) {

          // Load the file via file.fid.

          $file1 = file_load($bg[$i][0]);

          // Change status to permanent.

          $file1->setPermanent();

          $file1->save();

          $file_usage = \Drupal::service('file.usage');

          $file_usage->add($file1, 'wristcheck', 'theme', 1);

        } 

    }

        

}

?>