<?php

namespace Drupal\starwars\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\starwars\Entity\PlanetInterface;

/**
 * Class PlanetController.
 *
 *  Returns responses for Planet routes.
 */
class PlanetController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Planet  revision.
   *
   * @param int $starwars_planet_revision
   *   The Planet  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($starwars_planet_revision) {
    $starwars_planet = $this->entityManager()->getStorage('starwars_planet')->loadRevision($starwars_planet_revision);
    $view_builder = $this->entityManager()->getViewBuilder('starwars_planet');

    return $view_builder->view($starwars_planet);
  }

  /**
   * Page title callback for a Planet  revision.
   *
   * @param int $starwars_planet_revision
   *   The Planet  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($starwars_planet_revision) {
    $starwars_planet = $this->entityManager()->getStorage('starwars_planet')->loadRevision($starwars_planet_revision);
    return $this->t('Revision of %title from %date', ['%title' => $starwars_planet->label(), '%date' => format_date($starwars_planet->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Planet .
   *
   * @param \Drupal\starwars\Entity\PlanetInterface $starwars_planet
   *   A Planet  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(PlanetInterface $starwars_planet) {
    $account = $this->currentUser();
    $langcode = $starwars_planet->language()->getId();
    $langname = $starwars_planet->language()->getName();
    $languages = $starwars_planet->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $starwars_planet_storage = $this->entityManager()->getStorage('starwars_planet');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $starwars_planet->label()]) : $this->t('Revisions for %title', ['%title' => $starwars_planet->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all planet revisions") || $account->hasPermission('administer planet entities')));
    $delete_permission = (($account->hasPermission("delete all planet revisions") || $account->hasPermission('administer planet entities')));

    $rows = [];

    $vids = $starwars_planet_storage->revisionIds($starwars_planet);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\starwars\PlanetInterface $revision */
      $revision = $starwars_planet_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $starwars_planet->getRevisionId()) {
          $link = $this->l($date, new Url('entity.starwars_planet.revision', ['starwars_planet' => $starwars_planet->id(), 'starwars_planet_revision' => $vid]));
        }
        else {
          $link = $starwars_planet->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.starwars_planet.translation_revert', ['starwars_planet' => $starwars_planet->id(), 'starwars_planet_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.starwars_planet.revision_revert', ['starwars_planet' => $starwars_planet->id(), 'starwars_planet_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.starwars_planet.revision_delete', ['starwars_planet' => $starwars_planet->id(), 'starwars_planet_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['starwars_planet_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
