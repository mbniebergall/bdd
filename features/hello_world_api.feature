Feature:
  As a welcoming person
  I want to say hello to someone by name
  So that they feel welcomed
  
  @test
  Scenario: Simple Test
    
    Given I request "GET /api/helloworld/SunshinePHP"
    Then I should get a "200" response

  Scenario: I should say Hello and someone's name

    Given I request "GET /api/helloworld/Alice"
    Then I should get a "200" response
    And The "greeting" field should be "Hello Alice"

  @failure
  Scenario: I should get an error with too short of a name

    Given I request "GET /api/helloworld/H"
    Then I should get a 422 response
    And The "title" field should be "Unprocessable Entity"

  Scenario: I should say Hello to anyone's name

    Given I store a fake "firstName" in the variable %firstName%
    When I request "GET /api/helloworld/%firstName%"
    Then I should get a 200 response
    And The "greeting" field should be "Hello %firstName%"

  @noDigits
  Scenario: The name should not include numbers

    Given I request "GET /api/helloworld/SunshinePHP2020"
    Then I should get a 422 response
    And The "detail" field should be "Name must not include digits"
