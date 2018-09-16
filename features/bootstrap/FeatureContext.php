<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    public $generatedEmail;
    public $buildPath;
    public $emailFileName;

    /**
     * Initialize context.
     */
    public function __construct()
    {
        $this->buildPath = substr(__DIR__, 0, -18).'build';
        $this->emailFileName = 'email.txt';
    }

    /**
     * @Given /^(?:|I )wait "([^"]*)" seconds$/
     *
     * @param int $delaySeconds
     */
    public function iWaitSeconds($delaySeconds)
    {
        sleep($delaySeconds);
    }

    /**
     * @param string $xpath
     *
     * @throws Exception
     */
    public function clickOn($xpath)
    {
        $this->waitForElementAbsenceOrPresence($xpath);
        $this->getSession()->getDriver()->click($xpath);
        time_nanosleep(1, 0);
    }

    /**
     * Click button in the registration page.
     *
     * @When /^(?:|I )click the button "([^"]*)"$/
     *
     * @param string $button
     *
     * @throws Exception
     */
    public function clickTheButton($button)
    {
        $this->clickOn("//div/button[@id='$button']");
        if (!$button) {
            throw new Exception("There is no $button' on the page");
        }
    }

    /**
     * @Then /^(?:|I )enter "([^"]*)" in field "([^"]*)"$/
     *
     * @param string|int|float $value
     * @param string           $xpath
     *
     * @throws \Behat\Mink\Exception\DriverException
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     * @throws Exception
     */
    public function enterInField($value, $xpath)
    {
        $this->getSession()->getDriver()->focus($xpath);
        $this->getSession()->getDriver()->setValue($xpath, $value);
    }

    /**
     * @Then /^(?:|I )switch to new window$/
     */
    public function switchToNewWindow()
    {
        $windows = $this->getSession()->getDriver()->getWindowNames();
        $nmb = count($windows) - 1;
        $this->getSession()->getDriver()->switchToWindow($windows[$nmb]);
    }

    /**
     * Waits for absence or presence of element depending on $waitForAbsence flag.
     * If you need to wait until element disappears use $waitForAbsence = 'true'.
     * If you need to wait for appearing of element use $waitForAbsence = 'false' or leave $waitForAbsence empty (because 'false' is default value).
     *
     * @param string $locator        - xpath or css value
     * @param string $type           - 'css' or 'xpath'(default)
     * @param int    $timeoutSeconds - number of seconds this method will wait. After this time runs out an Exception will be thrown.
     * @param bool   $waitForAbsence - if 'true' - waits for absence, if 'false' (default) waits for presence.
     *
     * @throws Exception
     */
    public function waitForElementAbsenceOrPresence($locator, $type = 'xpath', $timeoutSeconds = 30, $waitForAbsence = false)
    {
        $intervalMs = 100;
        $elapsedMs = 0;

        while ($elapsedMs < ($timeoutSeconds * 1000)) {
            $element = $this->getSession()->getPage()->find($type, $locator);
            if (($waitForAbsence && !$element) || (!$waitForAbsence && $element)) {
                return;
            }
            time_nanosleep(0, $intervalMs * 1000000);
            $elapsedMs += $intervalMs;
        }

        throw new Exception($waitForAbsence ? "Element that was supposed to be absent was found: '$locator' after $timeoutSeconds seconds of waiting!" : "Element that was supposed to be present was not found:: '$locator' after $timeoutSeconds seconds of waiting!");
    }

    /**
     * Generate unique e-mail and write it down to file.
     * It is needed in order to use it other scenarios because each scenario has it's own context and fields can not be passed from one to another.
     *
     * @Given /^(?:|I )generate random email$/
     */
    public function iGenerateRandomEmail()
    {
        $this->generatedEmail = 'mdenisenko'.date('mdHi').'@mailgutter.com';
        if (!file_exists($this->buildPath)) {
            mkdir($this->buildPath);
        }
        file_put_contents($this->buildPath.'/'.$this->emailFileName, $this->generatedEmail);
        echo "Generated email: {$this->generatedEmail}";
    }

    /**
     * @Given /^(?:|I )set generated email(?:| on "([^"]*)" page)$/
     *
     * @throws Exception
     */
    public function setGeneratedEmailOnPage()
    {
        $emailInputXpath = "//input[@id='email']";
        $generatedEmail = file_get_contents($this->buildPath.'/'.$this->emailFileName);
        $this->enterInField($generatedEmail, $emailInputXpath);
    }

    /**
     * Selecting radio button on registration page
     *
     * @When /^(?:|I )choose account type "([^"]*)"$/
     *
     * @param string $accountType
     *
     * @throws Exception
     */
    public function chooseAccountType($accountType)
    {
        $accountTypeXpath = "//label/span[@class='radio__label' and contains(text(),'$accountType')]";
        $this->clickOn($accountTypeXpath);
    }

    /**
     * Checking boxes on registration page
     *
     * @When /^(?:|I )check the "([^"]*)" box$/
     *
     * @param string $checkboxName
     *
     * @throws Exception
     */
    public function checkTheBox($checkboxName)
    {
       if ($checkboxName === 'Mainstream') {
           $checkboxXpath = "//label/span[@class='checkbox__label' and contains(text(),'Mainstream')]";
           $this->clickOn($checkboxXpath);
       }
       if ($checkboxName === 'Banners') {
           $checkboxXpath = "//label/span[@class='checkbox__label' and contains(text(),'Banners')]";
           $this->clickOn($checkboxXpath);
       }
       if ($checkboxName === 'I agree to receive special offers...') {
           $checkboxXpath = "(//div/div[@class='confirm-checkbox__check'])[1]";
           $this->clickOn($checkboxXpath);
       }
       if ($checkboxName === 'I agree with my data processing...') {
           $checkboxXpath = "(//div/div[@class='confirm-checkbox__check'])[2]";
           $this->clickOn($checkboxXpath);
       }
       if ($checkboxName === 'I have a VAT ID') {
           $checkboxXpath = "//label/span[@class='checkbox__label' and contains(text(),'I have a VAT ID')]";
           $this->clickOn($checkboxXpath);
       }
    }

    /**
     * Special step checks mail box on https://mailgutter.com/
     * If mailbox with generated e-mail address is empty an exception will be thrown.
     *
     * @Given /^(?:|I )check mailbox at mailgutter and follow link$/
     *
     * @throws Exception
     */
    public function checkMailbox()
    {
        $emailAddressInputXpath = "//form/input[@id='keyword']";
        $checkButton = "//form/input[@class='button']";
        $incomingEmailXpath = "//div/div[contains(text(),'Clickadu')]";
        $email = file_get_contents($this->buildPath.'/'.$this->emailFileName);
        $this->enterInField($email, $emailAddressInputXpath);
        $this->clickOn($checkButton);
        $incomingEmail = $this->getSession()->getPage()->find('xpath', $incomingEmailXpath);
        if (!$incomingEmail) {
            throw new Exception("Mailbox '$email' is empty! No email received");
        }
        $this->clickOn($incomingEmailXpath);
        $this->clickOn("//img[@src ='https://www.clickadu.com/static/images/balance/activate_account.png']");
        $this->switchToNewWindow();
    }

    /**
     * Clicking on red information icon when we get error
     *
     * @Given /^(?:|I )click on information icon in the red field$/
     *
     * @throws Exception
     */
    public function clickOnInformationIcon()
    {
        $informationIconXpath = "//pa-error-label/div[@class='form-group__input-errors']";
        $this->clickOn($informationIconXpath);
    }

    /**
     * Checking error on fields
     *
     * @Given /^(?:|I )see error in "([^"]*)" field$/
     *
     * @param string $fieldName
     *
     * @throws Exception
     */
    public function seeErrorInField($fieldName)
    {
        if ($fieldName === 'companyName') {
            $errorXpath = "//div/div[@class='form-group__input  form-group_error']/descendant::pa-error-label/div";
        }
        else if ($fieldName === 'nickname'){
            $errorXpath = "//div/div[@class='form-group__input']/descendant::pa-error-label/div";
        } else {
            $errorXpath = "//div/pa-labeled-input[@name='$fieldName']/descendant::pa-error-label/div";
        }
        $this->clickOn($errorXpath);
    }
}