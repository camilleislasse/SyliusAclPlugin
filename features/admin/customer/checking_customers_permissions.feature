@checking_customers_permissions
Feature: Checking customers permissions
    In order to check permissions to customers
    As an Administrator
    I want to check permissions to customers

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a customer group Retail
        And there is a customer account "f.baggins@shire.me"
        And there is a role "Basic"
        And I am logged in as an administrator
        And this administrator has permissions check enabled

    @ui
    Scenario: Showing the sidebar elements to which the permissions are
        Given this administrator has the "Basic" role with "index" permissions for the "customers"
        When I open administration dashboard
        Then I should see 6 sidebar elements
        And I should see a reference to "Customers" in the sidebar

    @ui
    Scenario: Showing the sidebar elements without permissions
        When I open administration dashboard
        Then I should see 5 sidebar elements
        And I should not see a reference to "Customers" in the sidebar

    @ui @javascript
    Scenario: Trying to see buttons without permission
        Given this administrator has the "Basic" role with "show" permissions for the "customers"
        When I view details of the customer "f.baggins@shire.me"
        Then I should not see the buttons "Edit, Show orders, Delete"

    @ui @javascript
    Scenario: Trying to see buttons with permission
        Given this administrator has the "Basic" role with "show, update, delete" permissions for the "customers"
        When I view details of the customer "f.baggins@shire.me"
        Then I should see the buttons "Edit, Delete"
        And I should not see the button "Show orders"
