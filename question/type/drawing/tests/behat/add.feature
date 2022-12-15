@qtype @qtype_drawing
Feature: Test creating an drawing question
  As a teacher
  In order to test my students
  I need to be able to create an drawing question

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email               |
      | teacher1 | T1        | Teacher1 | teacher1@moodle.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" node in "Course administration"

  Scenario: Create an drawing question with Response format set to 'HTML editor'
    When I add a "drawing" question filling the form with:
      | Question name            | drawing-001                      |
      | Question text            | Draw a biology cell. |
      | General feedback         | This is general feedback       |
    Then I should see "drawing-001"
