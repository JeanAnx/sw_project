<?php

namespace Drupal\starwars\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\starwars\Entity\PeopleEntityInterface;

/**
 * Class PeopleEntityController.
 *
 *  Returns responses for People entity routes.
 */
class PeopleEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a People entity  revision.
   *
   * @param int $people_entity_revision
   *   The People entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($people_entity_revision) {
    $people_entity = $this->entityManager()->getStorage('people_entity')->loadRevision($people_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('people_entity');

    return $view_builder->view($people_entity);
  }

  /**
   * Page title callback for a People entity  revision.
   *
   * @param int $people_entity_revision
   *   The People entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($people_entity_revision) {
    $people_entity = $this->entityManager()->getStorage('people_entity')->loadRevision($people_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $people_entity->label(), '%date' => format_date($people_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a People entity .
   *
   * @param \Drupal\starwars\Entity\PeopleEntityInterface $people_entity
   *   A People entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(PeopleEntityInterface $people_entity) {
    $account = $this->currentUser();
    $langcode = $people_entity->language()->getId();
    $langname = $people_entity->language()->getName();
    $languages = $people_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $people_entity_storage = $this->entityManager()->getStorage('people_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $people_entity->label()]) : $this->t('Revisions for %title', ['%title' => $people_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all people entity revisions") || $account->hasPermission('administer people entity entities')));
    $delete_permission = (($account->hasPermission("delete all people entity revisions") || $account->hasPermission('administer people entity entities')));

    $rows = [];

    $vids = $people_entity_storage->revisionIds($people_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\starwars\PeopleEntityInterface $revision */
      $revision = $people_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $people_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.people_entity.revision', ['people_entity' => $people_entity->id(), 'people_entity_revision' => $vid]));
        }
        else {
          $link = $people_entity->link($date);
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
              Url::fromRoute('entity.people_entity.translation_revert', ['people_entity' => $people_entity->id(), 'people_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.people_entity.revision_revert', ['people_entity' => $people_entity->id(), 'people_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.people_entity.revision_delete', ['people_entity' => $people_entity->id(), 'people_entity_revision' => $vid]),
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

    $build['people_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
