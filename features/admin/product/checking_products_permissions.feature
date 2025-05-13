@checking_products_permissions
Feature: Checking products permissions
    In order to check permissions to products
    As an Administrator
    I want to check permissions to products

    Background:
        Given the store has "Dice Brewing" and "Eclipse" products
        And the store operates on a single channel in "United States"
        And the store classifies its products as "T-Shirts", "Accessories", "Funny" and "Sad"
        And there is a role "Basic"
        And I am logged in as an administrator
        And this administrator has permissions check enabled

    @ui @javascript
    Scenario: Showing buttons to which the administrator has permission
        Given this administrator has the "Basic" role with "index, update" permissions for the "products"
        When I want to browse products
        Then I should see the button "Edit"
        And I should not see the buttons "Manage variants, Delete, Bulk delete, Create"

    @ui
    Scenario: Trying to edit the product without permission
        When I want to trying modify the "Dice Brewing" product
        Then I should get a 403 HTTP response

    @ui
    Scenario: Trying to edit the product with permission
        Given this administrator has the "Basic" role with "update" permissions for the "products"
        When I want to trying modify the "Dice Brewing" product
        Then I should get a 200 HTTP response

    @ui @javascript
    Scenario: Showing buttons to create the product and manage variants
        Given this administrator has the "Basic" role with "create_simple, index, bulk_delete" permissions for the "products"
        And this administrator has the "Basic" role with "index" permissions for the "product_variants"
        When I want to browse products
        Then I should see the button "Create, Delete, Manage variants"
        And I should not see the buttons "Edit"

    @ui
    Scenario: Trying to see taxon tree without permission
        Given this administrator has the "Basic" role with "index" permissions for the "products"
        When I want to browse products
        Then I should not see taxon tree

    @ui
    Scenario: Trying to see taxon tree with permission
        Given this administrator has the "Basic" role with "index" permissions for the "products"
        And this administrator has the "Basic" role with "taxon_tree" permissions for the "taxa"
        When I want to browse products
        Then I should see taxon tree

    @ui
    Scenario: Showing the sidebar elements to which the permissions are
        Given this administrator has the "Basic" role with "index" permissions for the "products"
        When I open administration dashboard
        Then I should see 6 sidebar elements
        And I should see a reference to "Products" in the sidebar

    @ui
    Scenario: Showing the sidebar elements without permissions
        When I open administration dashboard
        Then I should see 5 sidebar elements
        And I should not see a reference to "Products" in the sidebar

    @ui
    Scenario: Trying to edit the product without permission but is permissions checking disable
        Given this administrator has permissions check disable
        When I want to trying modify the "Dice Brewing" product
        Then I should get a 200 HTTP response

    @ui @javascript
    Scenario: Showing all buttons without permission when is permissions checking disable
        Given this administrator has permissions check disable
        When I want to browse products
        Then I should see the button "Edit, Manage variants, Delete, Create"

    @ui
    Scenario: Trying to see taxon tree without permission but is permissions checking disable
        Given this administrator has permissions check disable
        When I want to browse products
        Then I should see taxon tree
