<?php

namespace Drupal\mailchimp_ecommerce;

use Drupal\node\Entity\Node;
use Drupal\commerce_product\Entity\Product;

/**
 * Interface for the Product handler.
 */
interface ProductHandlerInterface {

  /**
   * Adds a product to Mailchimp.
   *
   * Adds a product variant if a product with the given ID exists.
   *
   * In Mailchimp, each product requires at least one product variant. This
   * function will create a single product variant when creating new products.
   *
   * A product variant is contained within a product and can be used to
   * represent shirt size, color, etc.
   *
   * @param string $product_id
   *   Unique ID of the product.
   * @param string $title
   *   The product title.
   * @param string $url
   *   The product url.
   * @param string $image_url
   *   The product image url.
   * @param string $description
   *   The product description.
   * @param string $type
   *   The product type.
   * @param array $variants
   *   An array of product variants. Structure defined in documentation below.
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/ecommerce/stores/products/#create-post_ecommerce_stores_store_id_products
   */
  public function addProduct($product_id, $title, $url, $image_url, $description, $type, $variants);

  /**
   * Updates an existing product in Mailchimp.
   *
   * Mailchimp only allows for product variants to be updated. The parent
   * product cannot be changed once created. This function will update the
   * variant associated with the given product ID and SKU.
   *
   * @param string $product_id
   *   Unique ID of the product.
   * @param string $title
   *   The product title.
   * @param string $url
   *   The product url.
   * @param string $image_url
   *   The product image url.
   * @param string $description
   *   The product description.
   * @param string $type
   *   The product type.
   * @param array $variants
   *   The product variants.
   *   May be identical to $product_id for single products.
   */
  public function updateProduct($product_id, $title, $url, $image_url, $description, $type, $variants);

  /**
   * Deletes a product in Mailchimp.
   *
   * @param string $product_id
   *   Unique ID of the product.
   */
  public function deleteProduct($product_id);

  /**
   * Adds a new product variant to Mailchimp.
   *
   * @param string $product_id
   *   Unique ID of the product.
   * @param string $product_variant_id
   *   ID of the product variant.
   * @param string $title
   *   The product title.
   * @param string $url
   *   The product url.
   * @param string $image_url
   *   The product image url.
   * @param string $sku
   *   The product SKU.
   * @param float $price
   *   The product price.
   * @param int $stock
   *   The stock total for a product.
   */
  public function addProductVariant($product_id, $product_variant_id, $title, $url, $image_url, $sku, $price, $stock);

  /**
   * Gets a product variant from Mailchimp.
   *
   * @param string $product_id
   *   Unique ID of the product.
   * @param string $product_variant_id
   *   ID of the product variant.
   *
   * @return object
   *   Mailchimp product variant object.
   */
  public function getProductVariant($product_id, $product_variant_id);

  /**
   * Deletes a product variant in Mailchimp.
   *
   * Automatically deletes the product if the only product variant is removed.
   *
   * @param string $product_id
   *   Unique ID of the product.
   * @param string $product_variant_id
   *   ID of the product variant.
   *   Can be identical to $product_id for single products.
   */
  public function deleteProductVariant($product_id, $product_variant_id);

  /**
   * Creates a URL from a Commerce product.
   *
   * @param Product $product
   *   The Commerce product object.
   *
   * @return string
   *   The URL of the product.
   */
  function buildProductUrl($product);

  /**
   * Creates a product URL from a node.
   *
   * @param Node $product
   *   The Commerce product object.
   *
   * @return string
   *   The URL of the product.
   */
  public function buildNodeUrl(Node $product);
}
