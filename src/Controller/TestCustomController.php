<?php
/**
 * @file
 * Controller for outputting JSON content for a Node.
 */

namespace Drupal\test_custom\Controller;

use Drupal\node\NodeInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
/**
 *
 */
class TestCustomController extends ControllerBase {

  /**
   * The Serializer.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * Constructs a new Serializer object.
   *
   * @param \Symfony\Component\Serializer\Serializer $serializer
   *   The Serializer.
   */
  public function __construct(Serializer $serializer) {
    $this->serializer = $serializer;
  }

  /**
   * Return JSON Response for Node.
   *
   * @param string $siteapikey
   *   A string containing Site Api Key from Configuration.
   * @param Drupal\node\NodeInterface $node
   *   The Node Object.
   *
   * @return json
   *   Return JSON Response.
   */
  public function json_content($siteapikey, NodeInterface $node) {

    $node_json = $this->serializer->serialize($node, 'json');
    return new JsonResponse($node_json);
  }

  /**
   * Function to check access.
   *
   * @param string $siteapikey
   *   A string containing Site Api Key from Configuration.
   * @param Drupal\node\NodeInterface $node
   *   The Node Object.
   *
   * @return boolean
   *   Return TRUE or FALSE depending on condition.
   */
  function access_json_content($siteapikey, NodeInterface $node) {

    $config_siteapikey = $this->config('test_custom.settings')->get('siteapikey');
    return AccessResult::allowedIf(($node->getType() == 'article') && (strcmp($siteapikey, $config_siteapikey) == 0));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('serializer'));
  }

}
