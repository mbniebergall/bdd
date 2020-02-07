Feature:
  As a mathematician
  I want to add numbers together
  So that I don't have to manually add numbers

  Scenario: I can add 2 positive integers

    Given I request "GET /api/adder/1/2"
    Then I should get a 200 response
    And The "sum" field should be "3"

  Scenario: I can add 2 random integers

    When I request "GET /api/adder/3/5"
    Then I should get a 200 response
    And The "sum" field should be "8"

    When I request "GET /api/adder/9/10"
    Then I should get a 200 response
    And The "sum" field should be "19"

  Scenario: The API should reject non-numeric input

    Given I request "GET /api/adder/abc/xyz123"
    Then I should get a 422 response

