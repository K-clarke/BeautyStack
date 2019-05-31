Feature:
  In order to complete the techtest
  As a candidate
  I want to have be able to use the api

  Background:

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/users" with body:
    """
    {
        "first_name": "Sharmadean",
        "last_name": "Reid",
        "email": "sharmadeanreid@beautystack.com",
        "pass": "BeautyStack",
        "roles": [
            "ROLE_CLIENT"
        ]
    }
    """

  Scenario: Adding a new user
    Then the response status code should be 201
    And the header "Content-Type" should be equal to "application/json"

  Scenario: Retrieving added users
    And I send a "GET" request to "api/v1/users"
    Then the response status code should be 200
    And the response should be in JSON

  Scenario: Search for style
    And I send a "GET" request to "api/v1/users?role=ROLE_CLIENT"
    Then the response status code should be 200
    And the response status code should not be 404
    And the response should be in JSON

  Scenario: Search for non existing sytle
    And I send a "GET" request to "api/v1/users?role=notexisting"
    And the response status code should be 404
    And the response should be in JSON
    And the JSON nodes should contain:
      | message                   | No Users Matching That Search Criteria              |
      | code                   | 404             |

  Scenario: Search for style
    And I send a "PATCH" request to "api/v1/users/sharmadeanreid@beautystack.com/promote"
    Then the response status code should be 204
    And the response status code should not be 404
