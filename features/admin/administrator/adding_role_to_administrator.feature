@managing_administrators_roles
Feature: Adding role to administrator
    In order to deny access for certain users to specific resources
    As an Administrator
    I want to be able add roles to the administrator

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And there is a role "Accountant"

    @ui @javascript
    Scenario: Adding a new administrator with a role
        Given I want to create a new administrator
        When I specify its email as "l.skywalker@gmail.com"
        And I specify its name as "Luke"
        And I specify its password as "lightsaber"
        And I specify its locale as "English (United States)"
        And I chose its "Accountant" role
        And I add it
        Then I should be notified that it has been successfully created
        And the administrator "l.skywalker@gmail.com" should have role "Accountant"
