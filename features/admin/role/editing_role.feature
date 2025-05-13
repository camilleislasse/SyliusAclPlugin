@managing_roles
Feature: Editing an role
    In order to change information about an role
    As an Administrator
    I want to be able to edit the role

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And there is a role "Accountant" with all permissions for "Tax rates" resource

    @ui @javascript
    Scenario: Changing name and permissions of an existing role
        When I modify a role "Accountant"
        And I change its name to "Main accountant"
        And I choose all permissions only for the "Orders" resource
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this role with name "Main accountant" should appear in the store
        And the role "Main accountant" should be have permission "Tax rates"
        And the role "Main accountant" should be have permission "Orders"
