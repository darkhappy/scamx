<?php

use Behat\Gherkin\Node\TableNode;
use Codeception\Actor;
use PHPUnit\Framework\IncompleteTestError;

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

  /**
   * @Given /^I am on the register page$/
   */
  public function iAmOnTheRegisterPage()
  {
    $this->amOnPage("/user/register");
  }

  /**
   * @Given /^I fill in the registration form with the following:$/
   */
  public function iFillInTheRegistrationFormWithTheFollowing(TableNode $table)
  {
    $this->fillField("username", $table->getRow(0)[1]);
    $this->fillField("password", $table->getRow(1)[1]);
    $this->fillField("confirm", $table->getRow(2)[1]);
    $this->fillField("email", $table->getRow(3)[1]);
  }

  /**
   * @Given /^the test user does not exist$/
   */
  public function theTestUserDoesNotExist()
  {
    throw new IncompleteTestError();
  }

  /**
   * @When /^I submit the form$/
   */
  public function iSubmitTheForm()
  {
    $this->submitForm("#form", []);
  }
}
