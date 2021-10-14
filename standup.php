<?php

// Get composer's help
require('vendor/autoload.php');

// Import the webdriver
use Facebook\WebDriver\Local\LocalWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

// How to find Google username and password on this machine
$googleAccountEmail 			= 'jcornell@elearninginnovation.com';
$macosKeychainEasePasswordName 	= 'ease'; // Must be in macOS Keychain under this name

// The Google Meet code for the standup
$standupMeetingCode				= 'mtu-nfto-oyh';

// Things Google has set and may change in the future
$googleSigninUrl				= 'https://accounts.google.com/signin/v2/identifier?ltmpl=meet&continue=https://meet.google.com?flowName=GlifWebSignIn&flowEntry=ServiceLogin';
$googleUsernameFieldDomId 		= 'identifierId';
$googleNextButtonDomId 			= 'identifierNext';
$googlePasswordFieldDomName 	= 'password';
$googlePasswordNextButtonDomId 	= 'passwordNext';
$googleMeetingCodeFieldDomId	= 'i3'; // Subject to change
$googleJoinButtonXpath			= '//span[text()="Join"]';

// Initialize handle on Chrome
$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['start-maximized']);
//$chromeOptions->addArguments(['use-fake-ui-for-media-stream']);
//$chromeOptions->addArguments(['use-fake-device-for-media-stream']);
$chromeOptions->addArguments(['disable-notifications']);
$driver = ChromeDriver::start();

// Start sign-in
$driver->get($googleSigninUrl);

// Get password from macOS Keychain
$password = exec("security find-generic-password -l $macosKeychainEasePasswordName -w");

// Login with username
$driver->findElement(WebDriverBy::id($googleUsernameFieldDomId))->sendKeys($googleAccountEmail);
$driver->findElement(WebDriverBy::id($googleNextButtonDomId))->click();
sleep(1); // Without this password field doesn't populate (because it takes too long to load?)
// There may be some way to do that by checking a DOM ready state
// May need to increase depending on machine's browsing CPU/network speed
$driver->findElement(WebDriverBy::name($googlePasswordFieldDomName))->sendKeys($password);
$driver->findElement(WebDriverBy::id($googlePasswordNextButtonDomId))->click();
sleep(2);
$driver->findElement(WebDriverBy::id($googleMeetingCodeFieldDomId))->sendKeys($standupMeetingCode);
$driver->findElement(WebDriverBy::xpath($googleJoinButtonXpath))->click();
exit;
