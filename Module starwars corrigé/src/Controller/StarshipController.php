<?php

namespace Drupal\starwars\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\starwars\Entity\StarshipInterface;

/**
 * Class StarshipController.
 *
 *  Returns responses for Starship routes.
 */
class StarshipController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Starship  revision.
   *
   * @param int $starwars_starship_revision
   *   The Starship  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($starwars_starship_revision) {
    $starwars_starship = $this->entityManager()->getStorage('starwars_starship')->loadRevision($starwars_starship_revision);
    $view_builder = $this->entityManager()->getViewBuilder('starwars_starship');

    return $view_builder->view($starwars_starship);
  }

  /**
   * Page title callback for a Starship  revision.
   *
   * @param int $starwars_starship_revision
   *   The Starship  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($starwars_starship_revision) {
    $starwars_starship = $this->entityManager()->getStorage('starwars_starship')->loadRevision($starwars_starship_revision);
    return $this->t('Revision of %title from %date', ['%title' => $starwars_starship->label(), '%date' => format_date($starwars_starship->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Starship .
   *
   * @param \Drupal\starwars\Entity\StarshipInterface $starwars_starship
   *   A Starship  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(StarshipInterface $starwars_starship) {
    $account = $this->currentUser();
    $langcode = $starwars_starship->language()->getId();
    $langname = $starwars_starship->language()->getName();
    $languages = $starwars_starship->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $starwars_starship_storage = $this->entityManager()->getStorage('starwars_starship');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $starwars_starship->label()]) : $this->t('Revisions for %title', ['%title' => $starwars_starship->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all starship revisions") || $account->hasPermission('administer starship entities')));
    $delete_permission = (($account->hasPermission("delete all starship revisions") || $account->hasPermission('administer starship entities')));

    $rows = [];

    $vids = $starwars_starship_storage->revisionIds($starwars_starship);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\starwars\StarshipInterface $revision */
      $revision = $starwars_starship_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $starwars_starship->getRevisionId()) {
          $link = $this->l($date, new Url('entity.starwars_starship.revision', ['starwars_starship' => $starwars_starship->id(), 'starwars_starship_revision' => $vid]));
        }
        else {
          $link = $starwars_starship->link($date);
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
              Url::fromRoute('entity.starwars_starship.translation_revert', ['starwars_starship' => $starwars_starship->id(), 'starwars_starship_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.starwars_starship.revision_revert', ['starwars_starship' => $starwars_starship->id(), 'starwars_starship_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.starwars_starship.revision_delete', ['starwars_starship' => $starwars_starship->id(), 'starwars_starship_revision' => $vid]),
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

    $build['starwars_starship_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
