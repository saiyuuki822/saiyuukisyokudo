<?php

namespace Drupal\geolocation\Plugin\geolocation\MapCenter;

use Drupal\geolocation\MapCenterInterface;
use Drupal\geolocation\MapCenterBase;
use Drupal\geolocation\ViewsContextTrait;

/**
 * Derive center from boundary filter.
 *
 * @MapCenter(
 *   id = "views_boundary_filter",
 *   name = @Translation("Boundary filter"),
 *   description = @Translation("Fit map to boundary filter."),
 * )
 */
class ViewsBoundaryFilter extends MapCenterBase implements MapCenterInterface {

  use ViewsContextTrait;

  /**
   * {@inheritdoc}
   */
  public function getAvailableMapCenterOptions($context) {
    $options = [];

    if ($displayHandler = self::getViewsDisplayHandler($context)) {
      /** @var \Drupal\views\Plugin\views\filter\FilterPluginBase $filter */
      foreach ($displayHandler->getHandlers('filter') as $filter_id => $filter) {
        if ($filter->getPluginId() == 'geolocation_filter_boundary') {

          // Preserve compatibility to v1.
          $options['boundary_filter_' . $filter_id] = $this->t('Boundary filter') . ' - ' . $filter->adminLabel();
        }
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function alterMap(array $map, $center_option_id, array $center_option_settings, $context = NULL) {
    $map = parent::alterMap($map, $center_option_id, $center_option_settings, $context);

    if (!($displayHandler = self::getViewsDisplayHandler($context))) {
      return $map;
    }

    /** @var \Drupal\geolocation\Plugin\views\filter\ProximityFilter $handler */
    $handler = $displayHandler->getHandler('filter', substr($center_option_id, 16));
    if (
      isset($handler->value['lat_north_east'])
      && $handler->value['lat_north_east'] !== ""
      && isset($handler->value['lng_north_east'])
      && $handler->value['lng_north_east'] !== ""
      && isset($handler->value['lat_south_west'])
      && $handler->value['lat_south_west'] !== ""
      && isset($handler->value['lng_south_west'])
      && $handler->value['lng_south_west'] !== ""
    ) {
      $map['#attached'] = array_merge_recursive($map['#attached'], [
        'library' => [
          'geolocation/map_center.viewsBoundaryFilter',
        ],
        'drupalSettings' => [
          'geolocation' => [
            'maps' => [
              $map['#id'] => [
                'map_center' => [
                  'views_boundary_filter' => [
                    'latNorthEast' => (float) $handler->value['lat_north_east'],
                    'lngNorthEast' => (float) $handler->value['lng_north_east'],
                    'latSouthWest' => (float) $handler->value['lat_south_west'],
                    'lngSouthWest' => (float) $handler->value['lng_south_west'],
                  ],
                ],
              ],
            ],
          ],
        ],
      ]);
    }

    return $map;
  }

}
