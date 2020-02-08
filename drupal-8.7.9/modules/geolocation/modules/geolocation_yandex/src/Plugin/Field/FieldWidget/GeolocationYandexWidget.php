<?php

namespace Drupal\geolocation_yandex\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\geolocation\Plugin\Field\FieldWidget\GeolocationMapWidgetBase;
use Drupal\Core\Render\BubbleableMetadata;

/**
 * Plugin implementation of the 'geolocation_googlegeocoder' widget.
 *
 * @FieldWidget(
 *   id = "geolocation_yandex",
 *   label = @Translation("Geolocation Yandex Map"),
 *   field_types = {
 *     "geolocation"
 *   }
 * )
 */
class GeolocationYandexWidget extends GeolocationMapWidgetBase {

  /**
   * {@inheritdoc}
   */
  static protected $mapProviderId = 'yandex';


  /**
   * {@inheritdoc}
   */
  static protected $mapProviderSettingsFormId = 'yandex_settings';

  /**
   * {@inheritdoc}
   */
  public function form(FieldItemListInterface $items, array &$form, FormStateInterface $form_state, $get_delta = NULL) {
    $element = parent::form($items, $form, $form_state, $get_delta);

    $element['#attributes']['data-widget-type'] = 'yandex';

    $element['#attached'] = BubbleableMetadata::mergeAttachments(
      $element['#attached'],
      [
        'library' => [
          'geolocation_yandex/widget.yandex',
        ],
      ]
    );

    return $element;
  }

}
