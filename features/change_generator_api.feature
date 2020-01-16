Feature:
  As a spender
  I want to receive the correct change
  So that I have some bling
  
  Scenario: A free item gives no change
    
    Given A request body with:
    """
    {
      "amount_due": 0,
      "tender": 0
    }
    """
    When I request "POST /api/change"
    Then The "change" field should be "0.00"

  Scenario: I pay the exact amount

    Given A request body with:
    """
    {
      "amount_due": 10,
      "tender": 10
    }
    """
    When I request "POST /api/change"
    Then The "change" field should be "0.00"

  Scenario: I pay more than what is due and get correct change back

    Given A request body with:
    """
    {
      "amount_due": 10,
      "tender": 20
    }
    """
    When I request "POST /api/change"
    Then The "change" field should be "10.00"

  Scenario: I get exact change back when I overpay

    Given A request body with:
    """
    {
      "amount_due": 1.25,
      "tender": 1.37
    }
    """
    When I request "POST /api/change"
    Then The "change" field should be "0.12"
