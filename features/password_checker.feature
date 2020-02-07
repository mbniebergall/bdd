Feature:
  As a system user
  I want my password strength enforced
  So that my password is not easily guessed


  Scenario: My password should be at least 8 characters

    Given A request body with:
    """
    {
      "password": "1234567"
    }
    """
    And I request "POST /api/passwordchecker"
    Then I should get a 422 response
    And The "detail" field should be "password must be at least 8 characters"


  Scenario: Common passwords should be rejected

    Given A request body with:
    """
    {
      "password": "password123"
    }
    """
    And I request "POST /api/passwordchecker"
    Then I should get a 422 response
    And The "detail" field should be "no common passwords"


  Scenario: Check for special characters

    Given A request body with:
    """
    {
      "password": "password4312"
    }
    """
    And I request "POST /api/passwordchecker"
    Then I should get a 422 response
    And The "detail" field should be "must have a special character"