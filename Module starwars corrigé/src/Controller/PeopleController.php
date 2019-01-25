<?php

namespace Drupal\starwars\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\starwars\Entity\PeopleInterface;

/**
 * Class PeopleController.
 *
 *  Returns responses for People routes.
 */
class PeopleController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a People  revision.
   *
   * @param int $starwars_people_revision
   *   The People  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($starwars_people_revision) {
    $starwars_people = $this->entityManager()->getStorage('starwars_people')->loadRevision($starwars_people_revision);
    $view_builder = $this->entityManager()->getViewBuilder('starwars_people');

    return $view_builder->view($starwars_people);
  }

  /**
   * Page title callback for a People  revision.
   *
   * @param int $starwars_people_revision
   *   The People  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($starwars_people_revision) {
    $starwars_people = $this->entityManager()->getStorage('starwars_people')->loadRevision($starwars_people_revision);
    return $this->t('Revision of %title from %date', ['%title' => $starwars_people->label(), '%date' => format_date($starwars_people->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a People .
   *
   * @param \Drupal\starwars\Entity\PeopleInterface $starwars_people
   *   A People  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(PeopleInterface $starwars_people) {
    $account = $this->currentUser();
    $langcode = $starwars_people->language()->getId();
    $langname = $starwars_people->language()->getName();
    $languages = $starwars_people->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $starwars_people_storage = $this->entityManager()->getStorage('starwars_people');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $starwars_people->label()]) : $this->t('Revisions for %title', ['%title' => $starwars_people->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all people revisions") || $account->hasPermission('administer people entities')));
    $delete_permission = (($account->hasPermission("delete all people revisions") || $account->hasPermission('administer people entities')));

    $rows = [];

    $vids = $starwars_people_storage->revisionIds($starwars_people);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\starwars\PeopleInterface $revision */
      $revision = $starwars_people_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $starwars_people->getRevisionId()) {
          $link = $this->l($date, new Url('entity.starwars_people.revision', ['starwars_people' => $starwars_people->id(), 'starwars_people_revision' => $vid]));
        }
        else {
          $link = $starwars_people->link($date);
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
              Url::fromRoute('entity.starwars_people.translation_revert', ['starwars_people' => $starwars_people->id(), 'starwars_people_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.starwars_people.revision_revert', ['starwars_people' => $starwars_people->id(), 'starwars_people_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.starwars_people.revision_delete', ['starwars_people' => $starwars_people->id(), 'starwars_people_revision' => $vid]),
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

    $build['starwars_people_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
