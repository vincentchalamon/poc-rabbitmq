Feature: I need to be able to use CRUD operations on foo API

    @createSchema @dropSchema
    Scenario: I can get a list of foo
        Given I send a GET request to "/foo"
        Then the response status code should be 200
        And the response should be in JSON

    @createSchema @dropSchema
    Scenario: I can create a foo
        Given I add "CONTENT_TYPE" header equal to "application/json"
        When I send a POST request to "/foo" with body:
        """
        {
            "name": "Hello World!",
            "isActive": false,
            "description": "Lorem ipsum dolor sit amet",
            "bar": {
              "name": "Test"
            }
        }
        """
        Then the response status code should be 201
        And the response should be in JSON

    @createSchema @dropSchema
    Scenario: I can get a foo
        Given I have a foo
        When I send a GET request to "/foo/1"
        And print last JSON response
        Then the response status code should be 200
        And the response should be in JSON

    @createSchema @dropSchema
    Scenario: I can update a foo
        Given I have a foo
        And I add "CONTENT_TYPE" header equal to "application/json"
        When I send a PATCH request to "/foo/1" with body:
        """
        {
            "isActive": true
        }
        """
        Then the response status code should be 200
        And the response should be in JSON

    @createSchema @dropSchema
    Scenario: I can delete a foo
        Given I have a foo
        When I send a DELETE request to "/foo/1"
        Then the response status code should be 204
        And the response should be empty
