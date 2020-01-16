Feature:
  As an API user
  I want to ping the web server
  So I can check that the server is available

  Scenario: I should be able to ping the web server

    Given I request "GET /api/ping"
    Then I should get a "200" response
    And The "ack" field should exist
