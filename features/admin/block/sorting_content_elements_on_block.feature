@managing_blocks
Feature: Sorting content elements on a block
    In order to manage the order of content on a block
    As an Administrator
    I want to be able to reorder content elements

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui @javascript
    Scenario: Moving a content element down
        When I go to the create block page
        And I fill the code with "sort-test-block"
        And I fill the name with "Sort Test Block"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        When I move the 1st content element down
        Then the 1st content element should be a "Textarea" element
        And the 2nd content element should be a "Heading" element

    @ui @javascript
    Scenario: Moving a content element up
        When I go to the create block page
        And I fill the code with "sort-test-block"
        And I fill the name with "Sort Test Block"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        When I move the 2nd content element up
        Then the 1st content element should be a "Textarea" element
        And the 2nd content element should be a "Heading" element

    @ui @javascript
    Scenario: Reordering keeps textarea contents at their new positions
        When I go to the create block page
        And I fill the code with "sort-test-block"
        And I fill the name with "Sort Test Block"
        And I add a textarea content element with "First textarea content" content
        And I add a textarea content element with "Second textarea content" content
        When I move the 2nd content element up
        Then the 1st content element should be a "Textarea" element
        And the 2nd content element should be a "Textarea" element
        And the 1st content element should contain "Second textarea content"
        And the 2nd content element should contain "First textarea content"

    @ui @javascript
    Scenario: Reordering keeps the selected media of adjacent autocomplete elements
        Given there is an existing media with names "Image 1" and "Image 2"
        When I go to the create block page
        And I fill the code with "sort-test-block"
        And I fill the name with "Sort Test Block"
        And I add a single media content element with name "Image 1"
        And I add a single media content element with name "Image 2"
        And I move the 2nd content element up
        Then the 1st content element should contain "Image 2"
        And the 2nd content element should contain "Image 1"

    @ui @javascript
    Scenario: The first content element cannot be moved up
        When I go to the create block page
        And I fill the code with "sort-test-block"
        And I fill the name with "Sort Test Block"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        Then the move up button of the 1st content element should be disabled

    @ui @javascript
    Scenario: The last content element cannot be moved down
        When I go to the create block page
        And I fill the code with "sort-test-block"
        And I fill the name with "Sort Test Block"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        Then the move down button of the 2nd content element should be disabled

    @ui @javascript
    Scenario: Moving a content element down keeps its content when editing an existing block
        Given there is a block "Sort Test Block" with a textarea content element with "First content" content and a textarea content element with "Second content" content
        When I want to edit this block
        And I move the 1st content element down
        Then the 1st content element should contain "Second content"
        And the 2nd content element should contain "First content"

    @ui @javascript
    Scenario: Moving a content element up keeps its content when editing an existing block
        Given there is a block "Sort Test Block" with a textarea content element with "First content" content and a textarea content element with "Second content" content
        When I want to edit this block
        And I move the 2nd content element up
        Then the 1st content element should contain "Second content"
        And the 2nd content element should contain "First content"

    @ui @javascript
    Scenario: Moving a content element down moves it one position only
        Given there is a block "Sort Test Block" with textarea content elements with "First content", "Second content" and "Third content" content
        When I want to edit this block
        And I move the 1st content element down
        Then the 1st content element should contain "Second content"
        And the 2nd content element should contain "First content"
        And the 3rd content element should contain "Third content"

    @ui @javascript
    Scenario: Moving a content element up moves it one position only
        Given there is a block "Sort Test Block" with textarea content elements with "First content", "Second content" and "Third content" content
        When I want to edit this block
        And I move the 3rd content element up
        Then the 1st content element should contain "First content"
        And the 2nd content element should contain "Third content"
        And the 3rd content element should contain "Second content"

    @ui @javascript
    Scenario: Moving the same content element down repeatedly
        Given there is a block "Sort Test Block" with textarea content elements with "First content", "Second content" and "Third content" content
        When I want to edit this block
        And I move the 1st content element down
        And I move the 2nd content element down
        Then the 1st content element should contain "Second content"
        And the 2nd content element should contain "Third content"
        And the 3rd content element should contain "First content"

    @ui @javascript
    Scenario: Moving a content element after inserting a new one in between
        Given there is a block "Sort Test Block" with a textarea content element with "First content" content and a textarea content element with "Second content" content
        When I want to edit this block
        And I insert a textarea content element after the 1st content element
        And I move the 3rd content element up
        Then the 1st content element should contain "First content"
        And the 2nd content element should contain "Second content"
        And the 3rd content element should be a "Textarea" element
        And the 3rd content element should be empty

    @ui @javascript
    Scenario: Inserting a content element between two existing elements
        When I go to the create block page
        And I fill the code with "insert-test-block"
        And I fill the name with "Insert Test Block"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        When I insert a textarea content element after the 1st content element
        Then the 1st content element should be a "Heading" element
        And the 2nd content element should be a "Textarea" element
        And the 3rd content element should be a "Textarea" element

    @ui @javascript
    Scenario: Inserting a content element before the first element
        When I go to the create block page
        And I fill the code with "insert-test-block"
        And I fill the name with "Insert Test Block"
        And I add a heading content element with type "h1" and "My Title" content
        And I add a textarea content element with "My body text" content
        When I insert a textarea content element before the 1st content element
        Then the 1st content element should be a "Textarea" element
        And the 2nd content element should be a "Heading" element
        And the 3rd content element should be a "Textarea" element
