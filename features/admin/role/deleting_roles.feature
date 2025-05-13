@managing_roles
Feature: Deleting an role
    In order to get rid of deprecated administrators
    As an Administrator
    I want to be able to delete role

    Background:
        Given I am logged in as an administrator
        And there is a role "Accountant"
        And there is a role "Promotions"

    @ui
    Scenario: Deleting an role
        Given I want to browse roles
        When I delete role with name "Promotions"
        Then I should be notified that it has been successfully deleted
        And there should not be "Promotions" role anymore
