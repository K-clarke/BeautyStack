Feature:
  In order to complete the techtest
  As a candidate
  I want to have be able to use the api

  Background:

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/styles" with body:
    """
    {
        "picture": "/var/folders/c0/y2nr7j110b38z8yhp0grr2tw0000gp/T/f344249d53ef995c7bc006c930d3f4a9.jpg",
        "description": "Bangs (or fringe) straight across the high forehead, or cut at a slight U-shape.",
        "price": 200.00,
        "time": "2004-02-05T08:11:47+00:00",
        "tag": [
            {
                "name": "Hair",
                "name": "Bangs",
                "name": "Fringe"
            }
        ]
    }
    """

  Scenario: Adding a new style
    Then the response status code should be 201
    And the header "Content-Type" should be equal to "application/json"

  Scenario: Retrieving added styles
    And I send a "GET" request to "api/v1/styles"
    Then the response status code should be 200
    And the response should be in JSON

  Scenario: Search for style
    And I send a "GET" request to "api/v1/styles?search=Bangs"
    Then the response status code should be 200
    And the response status code should not be 404
    And the response should be in JSON

  Scenario: Search for non existing sytle
    And I send a "GET" request to "api/v1/styles?search=notexisting"
    And the response status code should be 404
    And the response should be in JSON
    And the JSON nodes should contain:
      | message                   | No Styles Matching That Search Criteria              |
      | code                   | 404             |


