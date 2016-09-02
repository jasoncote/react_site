<?php

use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Drupal\DrupalExtension\Hook\Scope\BeforeNodeCreateScope;
use Behat\Testwork\Tester\Result\TestResult;

/**
 * Defines application features from the specific context.
 */
class T7HelperContext extends DrupalContext implements SnippetAcceptingContext {

  /**
   * The timestamp that the context was initialized.
   *
   * @var int
   */
  public $timestamp;

  public function __construct() {
    $this->timestamp = time();
  }


  /**
   * Before performing tests, prepare the site environment for testing.
   *
   * @param BeforeSuiteScope $scope
   *   The BeforeSuite scope object.
   *
   * @BeforeSuite
   */
  public static function setup(BeforeSuiteScope $scope) {
    // Save the original mail_system settings so we can restore them later, then
    // set the mail sysytem to TestingMailSystem.
    $mail_system = variable_get('mail_system', array());
    variable_set('mail_system_original', $mail_system);
    variable_set('mail_system', array('default-system' => 'TestingMailSystem'));
  }

  /**
   * After performing tests, return the site environment to its original
   * settings..
   *
   * @param AfterSuiteScope $scope
   *   The AfterSuite scope object.
   *
   * @AfterSuite
   */
  public static function teardown(AfterSuiteScope $scope) {
    // Restore the original mail_system settings.
    $mail_system = variable_get('mail_system_original', array('default-system' => 'DefaultMailSystem'));
    variable_set('mail_system', $mail_system);
    variable_del('mail_system_original');
  }

  /**
   * When a step fails, dump an HTML file representing the state of the page
   * at the time of the failure. If the Selenium driver is used, also save a
   * screenshot.
   *
   * @param AfterStepScope $event
   *   The AfterStep event object.
   *
   * @throws \Exception
   *
   * @AfterStep
   */
  public function recordFailedEvent(AfterStepScope $event) {
    // Determine if the current step is a failure.
    if ($event->getTestResult()->getResultCode() == TestResult::FAILED) {
      // Generate the contents of the HTML dump.
      $session = $this->getSession();
      $page = $session->getPage();
      $date = date('Y-m-d H:i:s');
      $base_url = $this->getMinkParameter('base_url');
      $current_url = $session->getCurrentUrl();
      $feature = $event->getFeature();
      $feature_filepath = (!empty($feature)) ? $feature->getFile() : 'failure';
      $feature_filepath = explode('/', $feature_filepath);
      $feature_filename = array_pop($feature_filepath);
      $step = $event->getStep();
      $step_text = $step->getText();
      $step_line = str_pad($step->getLine(), 4, '0', STR_PAD_LEFT);
      $html = $page->getContent();
      $html = "<!-- HTML dump from behat \nDate: $date \nURL: $current_url \nFile: $feature_filename \nLine: $step_line \nStep: $step_text \n-->" . $html;

      // Set the directory where we will put our failure dump file. This will be
      // relative to the Drupal root.
      $datestamp = date('Ymd-His', $this->timestamp);
      $failure_directory = 'failures/' . $datestamp;
      if (!file_exists($failure_directory)) {
        mkdir($failure_directory, 0755, TRUE);
      }

      // Build the filename for our HTML dump.
      $htmldump_filename = "$feature_filename.$step_line.html";

      // Save the failure HTML dump.
      $htmldump_filepath = $failure_directory . '/' . $htmldump_filename;
      file_put_contents($htmldump_filepath, $html);

      // Generate the feedback message to print to screen.
      $message = '';
      $message .= "\nHTML available at: $base_url/$htmldump_filepath";

      // If using the Selenium driver, also save a screenshot file.
      $driver = $session->getDriver();
      if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
        $screenshot = $driver->getScreenshot();
        $screenshot_filename = "$feature_filename.$step_line.png";
        $screenshot_filepath = $failure_directory . '/' . $screenshot_filename;
        file_put_contents($screenshot_filepath, $screenshot);
        $message .= "\nScreenshot available at: $base_url/$screenshot_filepath";
      }

      // Output the feedback message as an Exception, or else the "progress"
      // formatter will ignore it.
      throw new \Exception($message . PHP_EOL);
    }
  }

}
