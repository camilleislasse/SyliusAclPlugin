@managing_roles
Feature: Adding roles
    In order to manage permissions on my store
    As an Administrator
    I want to be able to add new roles

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui @javascript
    Scenario: Adding a role with all permissions
        When I go to the create role page
        And I fill the code with "super_admin"
        And I fill the name with "Super admin"
        And I choose all permissions
        And I add it
        Then I should be notified that the role has been created
        And the role "Super admin" should have all permissions

    @ui @javascript
    Scenario: Adding a role with all permissions for products resource
        When I go to the create role page
        And I fill the code with "super_admin"
        And I fill the name with "Super admin"
        And I choose all permissions only for the "Products" resource
        And I add it
        Then I should be notified that the role has been created
        And the role "Super admin" should have all permissions for "products" resource

    @ui
    Scenario: Trying to add a role with an existing code
        Given there is an existing role with "super_admin" code
        When I go to the create role page
        And I fill the code with "super_admin"
        And I try to add it
        Then I should be notified that there is already an existing role with provided code

    @ui
    Scenario: Trying to add role with blank data
        When I go to the create role page
        And I try to add it
        Then I should be notified that "Code, Name" fields cannot be blank

    @ui
    Scenario: Trying to add role with too long data
        When I go to the create role page
        And I fill "Code, Name" fields with 6000 characters
        And I try to add it
        Then I should be notified that "Code, Name" fields are too long
