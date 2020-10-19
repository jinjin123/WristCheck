<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\facets\Exception\Exception;
use Drupal\user\UserAuthInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\profile\Entity\Profile;

/**
 * Class UserController.
 */
class UserController extends ControllerBase
{


  /**
   * Dashboard.
   *
   * @return array
   *   Return Hello string.
   */
  public function info()
  {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $variables['mail'] = $user->getEmail();
    $variables['phone'] = $user->get('field_phone_number')->value;
    if(count($user->field_surnames->getValue())>0){
      $variables['surname'] = array_values($user->field_surnames->getValue()[0])[0];
    }
    $variables['gender']  = array_values($user->field_gender->getValue()[0])[0];
    if(count($user->field_date_of_birth->getValue())>0){
      $variables['birth']  = date("Y/m/d",strtotime(array_values($user->field_date_of_birth->getValue()[0])[0]));
    }
    $variables['name'] =$user->getUsername();
    $variables['lang'] = \Drupal::languageManager()->getCurrentLanguage()->getName();
    $variables['news'] = $user->get('field_newsletter')->value;
    $variables['cooperate'] = $user->get('field_we_cooperate_in')->value;
    $variables['guide'] = $user->get('field_guide')->value;
    $variables['price'] = $user->get('field_price_warning')->value;
    $entity_ids = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'portfolio')
      ->condition('uid', '1')
      ->sort('created.value', 'DESC')
      ->range(0, 1)->execute();
    $variables['porttime'] =  date("M d,Y",\Drupal\node\Entity\Node::load(array_values($entity_ids)[0])->created->getValue()[0]['value']);
    return [
      '#theme' => 'wristcheck_user_info',
      '#variables' => $variables
    ];
  }

  public function buy()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_buy',
      '#variables' => $variables
    ];
  }

  public function sell()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_sell',
      '#variables' => $variables
    ];

  }

  public function portfolio(){
//    $query = \Drupal::entityQuery('node')
//      ->condition('status', 1)
//      ->condition('uid', 1)
//      ->condition('type', 'portfolio');
//    $entity_ids = $query->execute();
    $entity_ids = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['type' => 'portfolio','uid'=>\Drupal::currentUser()->id()]);
    if(count($entity_ids)>0){
      $total = 0;
      $unit = "";
      foreach($entity_ids as $v){
        if(count($v->field_related_model)!=0){
          if(!empty($v->field_related_model->entity)){
            if (count(array_values($v->field_related_model->entity->field_ask_price->getValue()))>0){
              $total += array_values($v->field_related_model->entity->field_ask_price->getValue())[0]['number'];
              $unit = array_values($v->field_related_model->entity->field_ask_price->getValue())[0]['currency_code'];
            }
            if($unit !=""){
              $variables["total"] = "VALUE: ".$unit."".strval($total);
            }else {
              $variables = [];
            }
          }else{
            $variables = [];
          }
        }
      }
    }else{
      $variables = [];
    }
    $variables["userid"] = \Drupal::currentUser()->id();
    return [
      '#theme' => 'wristcheck_user_portfolio',
      '#variables' => $variables
    ];
  }

  public function usersupplement()
  {
    $user = \Drupal\user\Entity\User::load('1');
    $variables['mail'] = $user->getEmail();
    if (!$user->user_picture->isEmpty() !="") {
      $variables['picture'] = file_create_url($user->user_picture->entity->getFileUri());
//      $variables['picture'] = $user->user_picture->view('large');
    }else{
      $variables['picture']  = '';
    }

    return [
      '#theme' => 'wristcheck_user_supplement_form',
      '#variables' => $variables
    ];
  }

  public function useractivate()
  {
    $user = \Drupal\user\Entity\User::load('1');
    $variables['mail'] = $user->getEmail();
    return [
      '#theme' => 'wristcheck_user_useractivate',
      '#variables' => $variables
    ];
  }

  public function  userprofile()
  {
    try{
      $name = \Drupal::request()->request->get('names');
      $last_name = \Drupal::request()->request->get('last_name');
      $sex = \Drupal::request()->request->get('sex_selection');
      $profile = Profile::create([
        'type' => 'customer',
        'uid' => \Drupal::currentUser()->id(),
        'address' => [
          'country_code' => '--',
          'address_line1' => '--',
          'locality' => '--',
          'administrative_area' => '--',
          'postal_code' => '--',
          'family_name' => $name,
          'given_name' => $last_name,
        ],
        'field_sex'=>$sex,
      ]);
      $profile->save();
//      \Drupal::messenger()->addStatus("Save your profile successful!");
//      \Drupal::logger('User_profile')->error('user profile save faild' . $name.$last_name.$sex);
      return new Response("ok");
    }catch (Exception $e){
      return new Response("faild",403);
    }
  }

  /**
   * Create this page, just for redirect.
   * Because it is not redirect page right after login with $form_state->setRedirect('...')
   * or use hook_user_login.
   * And also I tried EventSubscriber, like the patch in
   * https://www.drupal.org/project/redirect_after_login/issues/3055452#comment-13726776
   * So I change the user login form '#acton', $form["#action"] .= '&destination=/wristcheck_basic/check_user_info';
   * Do redirect action in this page.
   */
  public function checkUserInfo() {
    $user = \Drupal::currentUser();
    $uid = $user->id();
    if (!wristcheck_basic_check_user_infomation($uid)) {
      //$response = new RedirectResponse(URL::fromUserInput('/user/' . $uid . '/edit')
      $response = new RedirectResponse(URL::fromRoute('user.register')
        ->toString());
      $response->send();
      exit;
    }
    else {
      $response = new RedirectResponse(URL::fromUserInput('/node/add/wcshw')
        ->toString());
      $response->send();
    }
    //return new Response("Check user info");
  }

  public function  flagdel($product_id) {
    $uid = \Drupal::currentUser()->id();
    $database = \Drupal::database();
    $query = $database->select("flagging","n")
      ->condition("n.uid",$uid,"=")
      ->condition("n.entity_id",$product_id,"=")
      ->countQuery()
      ->execute()->fetchCol()[0];
     if($query==1){
       $query = $database->delete("flagging")
         ->condition("entity_id",$product_id)
         ->condition("uid",$uid)
          ->execute();
     }else {
       \Drupal::messenger()->addError("Not Allow Opeation");
     }
    $response = new RedirectResponse('/user/'. $uid .'/wishlist');
     $response->send();
     return $response;
  }

}

