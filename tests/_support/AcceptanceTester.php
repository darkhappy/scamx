<?php

use Behat\Gherkin\Node\TableNode;
use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends Actor
{
  use _generated\AcceptanceTesterActions;

  /**
   * Define custom actions here
   */
  /**
   * @Given /^I am on the login page$/
   */
  public function iAmOnTheLoginPage()
  {
    $this->amOnPage("/user/login");
  }

  /**
   * @Given /^I fill in the login form with the following:$/
   */
  public function iFillInTheLoginFormWithTheFollowing(TableNode $table)
  {
    $this->fillField("username", $table->getRow(0)[1]);
    $this->fillField("password", $table->getRow(1)[1]);
  }

  /**
   * @When /^I press the login button$/
   */
  public function iPressTheLoginButton()
  {
    $this->click("Connecter");
  }

  /**
   * @Given /^I should see the home page$/
   */
  public function iShouldSeeTheHomePage()
  {
    $this->page("/");
  }

  /**
   * @Then /^I should see the login page$/
   */
  public function iShouldSeeTheLoginPage()
  {
    $this->amOnPage("/user/login");
  }

  /**
   * @Given /^I should see the message "([^"]*)"$/
   */
  public function iShouldSeeAMessage($arg1)
  {
    $this->see($arg1, "#alert");
  }

  /**
   * @Given /^I should see a success message$/
   */
  public function iShouldSeeASuccessMessage()
  {
    $this->see("", "#alert-success");
  }

  /**
   * @Given /^I should see an error message$/
   */
  public function iShouldSeeAnErrorMessage()
  {
    $this->see("", "#alert-error");
  }
}
