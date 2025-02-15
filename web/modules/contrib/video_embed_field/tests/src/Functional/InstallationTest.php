<?php

namespace Drupal\Tests\video_embed_field\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the modules install and uninstall cleanly.
 *
 * @group video_embed_field
 */
class InstallationTest extends BrowserTestBase {

  use AdminUserTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalLogin($this->createAdminUser());
  }

  /**
   * Test the installation and uninstallation of the the modules.
   */
  public function testInstallation() {
    $this->assertInstallationStatus(FALSE);
    $this->installModules();
    $this->assertInstallationStatus(TRUE);
    $this->uninstallModules();
    $this->assertInstallationStatus(FALSE);
    $this->installModules();
    $this->assertInstallationStatus(TRUE);
  }

  /**
   * Assert the installation status of the modules.
   *
   * @param bool $installed
   *   If the modules should be installed or not.
   */
  protected function assertInstallationStatus($installed) {
    $this->drupalGet('admin/modules');
    // @todo add video_embed_media once infrastructure places version
    // information in module info files.
    foreach (['video_embed_field'] as $module) {
      $this->assertSession()->{$installed ? 'checkboxChecked' : 'checkboxNotChecked'}('modules[' . $module . '][enable]');
    }
  }

  /**
   * Uninstall the module using the UI.
   */
  protected function uninstallModules() {
    $this->drupalGet('admin/modules/uninstall');
    $this->submitForm([
      'uninstall[video_embed_field]' => TRUE,
    ], 'Uninstall');
    $this->getSession()->getPage()->pressButton('Uninstall');
  }

  /**
   * Install the modules using the UI.
   */
  protected function installModules() {
    $this->drupalGet('admin/modules');
    $this->submitForm([
      'modules[video_embed_field][enable]' => TRUE,
    ], 'Install');
    // Continue is only required to confirm dependencies being enabled on the
    // first call of this function.
    if ($button = $this->getSession()->getPage()->findButton('Continue')) {
      $button->press();
    }
  }

}
