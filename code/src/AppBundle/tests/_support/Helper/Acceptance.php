<?php
namespace AppBundle\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    // FIXME: HACK to simulate parentGoToURL js function in popups
    // This function is used in popup buttons to  call
    // window.location = url in the parent
    // Selenium webdriver does not manage this case well
    public function pressButtonInIframe($buttonText, $iFrame)
    {
        $this->getModule('WebDriver')->switchToIFrame($iFrame);
        $buttonSelector = 'input[value="' . $buttonText . '"]';
        $this->getModule('WebDriver')->waitForElementVisible($buttonSelector, 10);
        $buttonElement = $this->getModule('WebDriver')->_findElements($buttonSelector);
        $onClickCode = trim($buttonElement[0]->getAttribute('onclick'));
        $start = strlen('parentGoToURL(');
        $urlStringToEvaluate = substr($onClickCode, $start, strlen($onClickCode) - $start - 2);
        $result = $this->getModule('WebDriver')->executeJs('return ' . $urlStringToEvaluate);
        $this->getModule('WebDriver')->switchToWindow();
        $this->getModule('WebDriver')->amOnPage('/' . $result);
    }

    public function clickOnOnTheRowContaining($linkName, $rowText)
    {
        /** @var $row \Behat\Mink\Element\NodeElement */
        $row = $this->getModule('WebDriver')->_findElements(sprintf('table tr:contains("%s")', $rowText));
        if (!$row) {
            throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowText));
        }
        $row->clickLink($linkName);
    }

    public function spoofSessionWithCookie($cookieValue)
    {
        $this->getModule('WebDriver')->setCookie(CATS_SESSION_NAME, $cookieValue);
    }
}
