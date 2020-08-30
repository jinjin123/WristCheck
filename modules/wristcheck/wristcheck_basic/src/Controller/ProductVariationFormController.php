<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\commerce_product\Entity\Product;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProductVariationFormController.
 */
class ProductVariationFormController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  protected $entityRepository;

  /**
   * ProductVariationFormController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Form\FormBuilderInterface         $form_builder
   * @param \Drupal\Core\Entity\EntityRepositoryInterface  $entity_repository
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FormBuilderInterface $form_builder, EntityRepositoryInterface $entity_repository) {
    $this->entityTypeManager = $entity_type_manager;
    $this->formBuilder = $form_builder;
    $this->entityRepository = $entity_repository;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder'),
      $container->get('entity.repository')
    );
  }


  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index($type, $product_id) {

    $output = [
      'data' => '',
      'message' => 'success',
      'status' => 1,
    ];

    if (!in_array($type, ['buy_new', 'buy_used'])) {
      $output['message'] = 'type not found';
      $output['status'] = 0;
      return new JsonResponse($output);
    }

    if (!$product = Product::load($product_id)) {
      $output['message'] = 'the product variation not found';
      $output['status'] = 0;
      return new JsonResponse($output);

    }

    $cart_array = $this->addToCartForm($product_id, 'teaser', true, 'en');
    $output['data'] = render($cart_array);
    return new JsonResponse($output);
  }

  /**
   * Builds the add to cart form.
   *
   * @param string $product_id
   *   The product ID.
   * @param string $view_mode
   *   The view mode used to render the product.
   * @param bool $combine
   *   TRUE to combine order items containing the same product variation.
   * @param string $langcode
   *   The langcode for the language that should be used in form.
   *
   * @return array
   *   A renderable array containing the cart form.
   */
  public function addToCartForm($product_id, $view_mode, $combine, $langcode) {
    /** @var \Drupal\commerce_order\OrderItemStorageInterface $order_item_storage */
    $order_item_storage = $this->entityTypeManager->getStorage('commerce_order_item');
    /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
    $product = $this->entityTypeManager->getStorage('commerce_product')->load($product_id);
    // Load Product for current language.
    $product = $this->entityRepository->getTranslationFromContext($product, $langcode);

    $default_variation = $product->getDefaultVariation();
    if (!$default_variation) {
      return [];
    }

    $order_item = $order_item_storage->createFromPurchasableEntity($default_variation);
    /** @var \Drupal\commerce_cart\Form\AddToCartFormInterface $form_object */
    $form_object = $this->entityTypeManager->getFormObject('commerce_order_item', 'add_to_cart');
    $form_object->setEntity($order_item);
    // The default form ID is based on the variation ID, but in this case the
    // product ID is more reliable (the default variation might change between
    // requests due to an availability change, for example).
    $form_object->setFormId($form_object->getBaseFormId() . '_commerce_product_' . $product_id);
    $form_state = (new FormState())->setFormState([
      'product' => $product,
      'view_mode' => $view_mode,
      'settings' => [
        'combine' => $combine,
      ],
    ]);

    return $this->formBuilder->buildForm($form_object, $form_state);
  }

}
