@managing_roles
Feature: Deleting multiple roles
    In order to get rid of deprecated roles in an efficient way
    As an Administrator
    I want to be able to delete multiple roles at once

    Background:
        Given I am logged in as an administrator
        And there is a role "Accountant"
        And there is a role "Promotions"
        And there is a role "Super admin"

    @ui @javascript
    Scenario: Deleting multiple roles at once
        Given I browse roles
        And I check the "Accountant" role
        And I check also the "Promotions" role
        And I delete them
        Then I should be notified that they have been successfully deleted
        And I should see a single role in the list
        And I should see the role "Super admin" in the list
