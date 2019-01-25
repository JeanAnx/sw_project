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
   * @param int $starship_revision
   *   The Starship  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($starship_revision) {
    $starship = $this->entityManager()->getStorage('starship')->loadRevision($starship_revision);
    $view_builder = $this->entityManager()->getViewBuilder('starship');

    return $view_builder->view($starship);
  }

  /**
   * Page title callback for a Starship  revision.
   *
   * @param int $starship_revision
   *   The Starship  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($starship_revision) {
    $starship = $this->entityManager()->getStorage('starship')->loadRevision($starship_revision);
    return $this->t('Revision of %title from %date', ['%title' => $starship->label(), '%date' => format_date($starship->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Starship .
   *
   * @param \Drupal\starwars\Entity\StarshipInterface $starship
   *   A Starship  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(StarshipInterface $starship) {
    $account = $this->currentUser();
    $langcode = $starship->language()->getId();
    $langname = $starship->language()->getName();
    $languages = $starship->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $starship_storage = $this->entityManager()->getStorage('starship');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $starship->label()]) : $this->t('Revisions for %title', ['%title' => $starship->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all starship revisions") || $account->hasPermission('administer starship entities')));
    $delete_permission = (($account->hasPermission("delete all starship revisions") || $account->hasPermission('administer starship entities')));

    $rows = [];

    $vids = $starship_storage->revisionIds($starship);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\starwars\StarshipInterface $revision */
      $revision = $starship_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $starship->getRevisionId()) {
          $link = $this->l($date, new Url('entity.starship.revision', ['starship' => $starship->id(), 'starship_revision' => $vid]));
        }
        else {
          $link = $starship->link($date);
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
              Url::fromRoute('entity.starship.translation_revert', ['starship' => $starship->id(), 'starship_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.starship.revision_revert', ['starship' => $starship->id(), 'starship_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.starship.revision_delete', ['starship' => $starship->id(), 'starship_revision' => $vid]),
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

    $build['starship_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
