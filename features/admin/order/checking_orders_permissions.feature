@checking_orders_permissions
Feature: Checking orders permissions
    In order to check permissions to orders
    As an Administrator
    I want to check permissions to orders

    Background:
        Given the store has "Dice Brewing" and "Eclipse" products
        And the store operates on a single channel in "United States"
        And the store has a product "Angel T-Shirt"
        And the store ships everywhere for free
        And the store allows paying with "Cash on Delivery"
        And there is a customer "lucy@teamlucifer.com" that placed an order "#00000777"
        And the customer bought a single "Angel T-Shirt"
        And the customer "Lucifer Morningstar" addressed it to "Seaside Fwy", "90802" "Los Angeles" in the "United States"
        And for the billing address of "Mazikeen Lilim" in the "Pacific Coast Hwy", "90806" "Los Angeles", "United States"
        And the customer chose "Free" shipping method with "Cash on Delivery" payment
        And there is a role "Basic"
        And I am logged in as an administrator
        And this administrator has permissions check enabled

    @ui
    Scenario: Trying to show the order without permission
        When I'm viewing the summary of the order "#00000777"
        Then I should get a 403 HTTP response

    @ui
    Scenario: Trying to show the order with permission
        Given this administrator has the "Basic" role with "show" permissions for the "orders"
        When I'm viewing the summary of the order "#00000777"
        Then I should get a 200 HTTP response

    @ui
    Scenario: Showing the sidebar elements to which the permissions are
        Given this administrator has the "Basic" role with "index, show, history, update" permissions for the "orders"
        When I open administration dashboard
        Then I should see 6 sidebar elements
        And I should see a reference to "Orders" in the sidebar

    @ui
    Scenario: Showing the sidebar elements without permissions
        When I open administration dashboard
        Then I should see 5 sidebar elements
        And I should not see a reference to "Orders" in the sidebar

    @ui @javascript
    Scenario: Trying to see buttons without permission
        Given this administrator has the "Basic" role with "show" permissions for the "orders"
        When I view the summary of the order "#00000777"
        Then I should not see the buttons "Ship, Complete, Edit, History, Cancel"

    @ui @javascript
    Scenario: Trying to see buttons with permission
        Given this administrator has the "Basic" role with "show, order_cancel, update" permissions for the "orders"
        When I view the summary of the order "#00000777"
        Then I should see the buttons "Edit, Cancel"
        And I should not see the buttons "Ship, Complete, History"

    @ui
    Scenario: Trying to finalizing order's payment with permission
        Given this administrator has the "Basic" role with "show, payment_complete" permissions for the "orders"
        And I view the summary of the order "#00000777"
        When I mark this order as paid
        Then I should be notified that the order's payment has been successfully completed
        And it should have payment state "Completed"

    @ui @javascript
    Scenario: Trying to see buttons without permission when is permissions checking disable
        Given this administrator has permissions check disable
        When I view the summary of the order "#00000777"
        Then I should see the buttons "Ship, Complete, Edit, History, Cancel"
