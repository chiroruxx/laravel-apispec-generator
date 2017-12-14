<?php

namespace ApiSpec;

use Illuminate\Foundation\Testing\TestResponse;

/**
 * @property string       method
 * @property string       uri
 * @property array        headers
 * @property TestResponse response
 * @property array        data
 * @property bool         isAuthenticated
 */
class ApiSpecObject
{
    protected $method = [];
    protected $uri = [];
    protected $headers = [];
    protected $response = null;
    protected $data = [];
    protected $isAuthenticated = false;

    public function output()
    {
        $content = $this->generateContent();

        $path = preg_replace('/https?:\/\/[0-9\.:a-zA-Z]+\//', '', $this->uri);
        $this->saveOutput($path . '/' . $this->method . '.http', $content);
    }

    public function generateContent()
    {
        // Uri
        $content = "$this->method $this->uri" . PHP_EOL;

        // Header
        foreach ($this->headers as $key => $value) {
            $content .= "$key: $value" . PHP_EOL;
        }
        if ($this->isAuthenticated) {
            // TODO select token protocol
            $content .= "Authorization: Bearer " . PHP_EOL;
        }

        $content .= PHP_EOL;

        // Content
        if (!empty($this->data)) {
            $param = \json_encode($this->data, JSON_PRETTY_PRINT);
            $content .= $param . PHP_EOL;
        }

        // Response
        $content .= "# Response:" . PHP_EOL . "#";
        $content .= mb_ereg_replace(
            PHP_EOL,
            PHP_EOL . '#',
            \json_encode($this->response->json(), JSON_PRETTY_PRINT));

        return $content;
    }

    public function saveOutput(string $filename, string $content)
    {
        $this->app['filesystem']->drive('local')->put($filename, $content);
    }

    //////////////////////
    // setters
    //////////////////////
    /**
     * @param string $method
     *
     * @return ApiSpecObject
     */
    public function setMethod(string $method): ApiSpecObject
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $uri
     *
     * @return ApiSpecObject
     */
    public function setUri(string $uri): ApiSpecObject
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return ApiSpecObject
     */
    public function setHeaders(array $headers): ApiSpecObject
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param TestResponse $response
     *
     * @return ApiSpecObject
     */
    public function setResponse(TestResponse $response): ApiSpecObject
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return ApiSpecObject
     */
    public function setData(array $data): ApiSpecObject
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param bool $isAuthenticated
     *
     * @return ApiSpecObject
     */
    public function setIsAuthenticated(bool $isAuthenticated): ApiSpecObject
    {
        $this->isAuthenticated = $isAuthenticated;

        return $this;
    }
}