<?php

defined( 'ABSPATH' ) || exit; 
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Curl based implementation of Google_IO.
 *
 * @author Stuart Langley <slangley@google.com>
 */

require_once 'Google/IO/Abstract.php';

class Google_IO_Curl extends Google_IO_Abstract
{
  // hex for version 7.31.0
  const NO_QUIRK_VERSION = 0x071F00;

  private $options = array();
  /**
   * Execute an HTTP Request
   *
   * @param Google_HttpRequest $request the http request to be executed
   * @return Google_HttpRequest http request with the response http code,
   * response headers and response body filled in
   * @throws Google_IO_Exception on curl or IO error
   */
  public function executeRequest(Google_Http_Request $request)
  {
    $url = $request->getUrl();
    $method = $request->getRequestMethod();
    $body = $request->getPostBody();
    $requestHeaders = $request->getRequestHeaders();

    // Prepare the arguments for wp_remote_request
    $args = array(
      'method'    => $method,
      'body'      => $body,
      'headers'   => $requestHeaders,
      'timeout'   => 60, // Set a timeout if needed
      'sslverify' => true, // Verify SSL
    );

    // Execute the request using WordPress's HTTP API
    $response = wp_remote_request($url, $args);

    // Check for errors
    if (is_wp_error($response)) {
      throw new Google_IO_Exception(esc_html($response->get_error_message()));
    }

    // Get the response body and headers
    $responseBody = wp_remote_retrieve_body($response);
    $responseCode = wp_remote_retrieve_response_code($response);
    $responseHeaders = wp_remote_retrieve_headers($response);

    return array($responseBody, $responseHeaders, $responseCode);
  }

  /**
   * Set options that update the transport implementation's behavior.
   * @param $options
   */
  public function setOptions($options)
  {
    $this->options = $options + $this->options;
  }

  /**
   * Set the maximum request time in seconds.
   * @param $timeout in seconds
   */
  public function setTimeout($timeout)
  {
    // Since this timeout is really for putting a bound on the time
    // we'll set them both to the same. If you need to specify a longer
    // CURLOPT_TIMEOUT, or a tigher CONNECTTIMEOUT, the best thing to
    // do is use the setOptions method for the values individually.
    $this->options[CURLOPT_CONNECTTIMEOUT] = $timeout;
    $this->options[CURLOPT_TIMEOUT] = $timeout;
  }

  /**
   * Get the maximum request time in seconds.
   * @return timeout in seconds
   */
  public function getTimeout()
  {
    return $this->options[CURLOPT_TIMEOUT];
  }

  /**
   * Determine whether "Connection Established" quirk is needed
   * @return boolean
   */
  protected function needsQuirk()
  {
    $ver = curl_version();
    $versionNum = $ver['version_number'];
    return $versionNum < Google_IO_Curl::NO_QUIRK_VERSION;
  }
}
