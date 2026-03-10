<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World routes.
 */
class HelloController extends ControllerBase {

  /**
   * Builds the Hello World page.
   */
  public function content() {
    return [
      '#theme' => 'hello_world',
      '#message' => $this->t('Hello, World!'),
    ];
  }

}