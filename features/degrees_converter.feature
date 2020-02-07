Feature:
  As a traveler
  I want to convert Celcius to Farenheight
  So I can understand the forecast


  Scenario: Require a number

    Given A request body with:
    """
    {
      "temperature": "35a"
    }
    """
    And I request "POST /api/degrees_converter"
    Then I should get a 422 response
    And The "detail" field should be "it must be a number"

  Scenario: Convert C to F

    Given A request body with:
    """
    {
      "temperature": "35"
    }
    """
    And I request "POST /api/degrees_converter"
    Then I should get a 200 response
    And The "degrees" field should be "95"

