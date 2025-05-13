@managing_roles
Feature: Browsing roles
    In order to see all roles in the store
    As an Administrator
    I want to browse roles

    Background:
        Given I am logged in as an administrator
        And there is a role "Accountant"
        And there is a role "Promotions"

    @ui
    Scenario: Browsing roles in store
        When I want to browse roles
        Then there should be 2 roles in the list
        And I should see the role "Accountant" in the list
