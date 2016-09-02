Feature: Visit the homepage
  In order to learn more about the organization
  As any user
  I need to be able to view the homepage

  Scenario: Navigate from the homepage
    Given I am an anonymous user
    And I am on the homepage
    Then I should see the link "Home" in the "Navigation" region
