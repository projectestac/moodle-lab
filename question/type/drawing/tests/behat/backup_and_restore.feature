@qtype @qtype_drawing
Feature: Test duplicating a quiz containing an drawing question
  As a teacher
  In order re-use my courses containing drawing questions
  I need to be able to backup and restore them

  Background:
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "questions" exist:
      | questioncategory | qtype     | name      | template         |
      | Test questions   | drawing     | drawing-001 | editor           |
      | Test questions   | drawing     | drawing-002 | editorfilepicker |
      | Test questions   | drawing     | drawing-003 | plain            |
    And the following "activities" exist:
      | activity   | name      | course | idnumber |
      | quiz       | Test quiz | C1     | quiz1    |
    And quiz "Test quiz" contains the following questions:
      | drawing-001 | 1 |
      | drawing-002 | 1 |
      | drawing-003 | 1 |
    And I log in as "admin"
    And I am on "Course 1" course homepage

  @javascript
  Scenario: Backup and restore a course containing 3 drawing questions
    When I backup "Course 1" course using this options:
      | Confirmation | Filename | test_drawing_backup.mbz |
    And I restore "test_drawing_backup.mbz" backup into a new course using this options:
      | Schema | Course name | Course 2 |
    And I navigate to "Question bank" node in "Course administration"
    And I should see "drawing-001"
    And I should see "drawing-002"
    And I should see "drawing-003"
    And I click on "Edit" "link" in the "drawing-001" "table_row"
    Then the following fields match these values:
      | Question name              | drawing-001                                               |
      | General feedback           | I hope your drawing had a beginning, a middle and an end. |
    And I press "Cancel"
    And I click on "Edit" "link" in the "drawing-002" "table_row"
    Then the following fields match these values:
      | Question name              | drawing-002                                               |
      | General feedback           | I hope your drawing had a beginning, a middle and an end. |
    And I press "Cancel"
    And I click on "Edit" "link" in the "drawing-003" "table_row"
    Then the following fields match these values:
      | Question name              | drawing-003                                               |
      | General feedback           | I hope your drawing had a beginning, a middle and an end. |
