<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /** @var Client */
    protected $client;

    /** @var Response */
    protected $response;

    /** @var string */
    protected $rawResponseBody;

    /** @var Request */
    protected $request;

    /** @var Collection */
    protected $responseBody;

    /** @var array */
    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    /** @var ?string */
    protected $body;

    /** @var Collection */
    protected $storedValues;

    /** @var \Faker\Generator */
    protected $faker;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(string $baseUrl)
    {
        $this->client = new Client(
            [
                'base_uri' => $baseUrl,
                'cookies' => true,
                'http_errors' => false,
                'verify' => false,
            ]
        );

        $this->storedValues = collect();


        $this->faker = Faker\Factory::create();
    }

    /**
     * @When /^I request "(.+) ([^"]+)"$/
     */
    public function iRequest(string $verb, string $url)
    {
        $options = [
            'body' => $this->prepareString($this->body) ?? null,
            'headers' => $this->prepareString($this->headers) ?? null,
        ];

        $url = $this->prepareString($url);

        try {
            $this->response = $this->client->request(
                $verb,
                $url,
                $options
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Assert::fail("Connection Failed to $verb $url\n" . $e->getMessage());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            Assert::fail("The server failed (500): \n" . $e->getResponse()->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->request = $e->getRequest();
            if ($e->hasResponse()) {
                $this->response = $e->getResponse();
            }
        } finally {
            if (isset($this->response)) {
                $this->rawResponseBody = $this->response->getBody()->getContents();
                $this->responseBody    = collect(json_decode($this->rawResponseBody));
            }
        }
    }

    /**
     * @Given /^The "([^"]*)" field should be "([^"]*)"$/
     */
    public function theFieldShouldBe(string $fieldPath, $value)
    {
        $body  = $this->theFieldShouldExist($fieldPath);
        $value = $this->prepareString($value);

        Assert::assertEquals(
            $this->prepareString($value),
            $body[0],
            "The '$fieldPath' field was not the same as the expected value '$value'. It was '" . $body[0] . "'"
        );
    }

    /**
     * @Given /^The "([^"]*)" field should exist$/
     */
    public function theFieldShouldExist(string $fieldPath)
    {
        $body = $this->responseBody;

        $fields = collect(explode('.', $this->prepareString($fieldPath)));

        $path = collect([]);

        while ($fields->count() !== 0) {
            $field = $fields->first();
            $path  = $path->merge([$field]);
            Assert::assertArrayHasKey(
                $field,
                $body,
                "Unable to find field '" .
                implode('.', $path->all()) . "'\n" .
                $this->responseBody->toJson(JSON_PRETTY_PRINT)
            );

            $body = collect($body->get($field));
            $fields = $fields->slice(1);
        }

        return $body;
    }

    /**
     * @Given /^I store a fake "(.*)" in the variable %(.*)%$/
     */
    public function iStoreAFakeValueInTheVariable($fakeName, $variable)
    {
        $this->storedValues[$variable] = [
            'search' => $variable,
            'replace' => $this->faker->$fakeName,
        ];
    }

    /**
     * @Given /^A request body with:$/
     */
    public function aRequestBodyWith(PyStringNode $string)
    {
        $this->body = (string) $string;
    }

    /**
     * @Then I should get a :httpStatusCode response
     */
    public function iShouldGetAResponse(string $httpStatusCode)
    {
        $code = $this->response->getStatusCode();

        Assert::assertEquals(
            $httpStatusCode,
            $code,
            "Unexpected response code: $code\n\n" . $this->getResponse()
        );
    }

    public function prepareString($string)
    {
        $prepared = $this->storedValues->reduce(
            function ($subject, $storedValue) {
                $search = '%' . $storedValue['search'] . '%';
                return str_replace($search, $storedValue['replace'], $subject);
            },
            $string
        );

        return $prepared;
    }

    protected function getResponse(): string
    {
        return $this->getResponseHeaders() . $this->getResponseBody();
    }

    protected function getResponseHeaders(): string
    {
        $response = '';
        $headers = $this->response->getHeaders();
        foreach ($headers as $name => $value) {
            if (is_string($name) && is_string($value)) {
                $response .= $name . ': ' . $value . "\n";
            }
        }

        return $response;
    }

    protected function getResponseBody(): string
    {
        $contentType = $this->response->getHeaderLine('Content-Type');

        $body = strpos($contentType, 'text/html') !== false
            ? $this->rawResponseBody
            : $this->responseBody->toJson(JSON_PRETTY_PRINT);

        return $body;
    }
}
