<?php

defined( 'ABSPATH' ) || exit; 
/*
 * Copyright 2013 Google Inc.
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
 * Http Streams based implementation of Google_IO.
 *
 * @author Stuart Langley <slangley@google.com>
 */

require_once 'Google/IO/Abstract.php';

class Google_IO_Stream extends Google_IO_Abstract
{
  const TIMEOUT = "timeout";
  const ZLIB = "compress.zlib://";
  private $options = array();

  private static $DEFAULT_HTTP_CONTEXT = array(
    "follow_location" => 0,
    "ignore_errors" => 1,
  );

  private static $DEFAULT_SSL_CONTEXT = array(
    "verify_peer" => true,
  );

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
    $args = array(
        'method'    => $request->getRequestMethod(),
        'user-agent'=> $request->getUserAgent(),
        'timeout'   => isset($this->options[self::TIMEOUT]) ? $this->options[self::TIMEOUT] : 45,
        'headers'   => $request->getRequestHeaders(),
        'sslverify' => true, // Enable SSL verification
    );

    // Check if there's a body to send with the request
    if ($request->getPostBody()) {
        $args['body'] = $request->getPostBody();
    }

    // Add custom CA certificate location if it's set in SSL context
    $default_options = stream_context_get_options(stream_context_get_default());
    $requestSslContext = array_key_exists('ssl', $default_options) ? $default_options['ssl'] : array();
    if (!array_key_exists("cafile", $requestSslContext)) {
        $args['sslcertificates'] = dirname(__FILE__) . '/cacerts.pem';
    } else {
        $args['sslcertificates'] = $requestSslContext['cafile'];
    }

    // Adjust URL if necessary
    $url = $request->getUrl();
    if ($request->canGzip()) {
        $url = self::ZLIB . $url; // Note: You need to handle gzip decompression if necessary
    }

    // Make the request
    $response = wp_remote_request($url, $args);

    // Check for WP_Error
    if (is_wp_error($response)) {
        throw new Google_IO_Exception(
            sprintf(
                "HTTP Error: Unable to connect: '%s'",
                esc_html($response->get_error_message())
            ),
            esc_html(wp_remote_retrieve_response_code($response))
        );
    }

    $respHttpCode = wp_remote_retrieve_response_code($response);
    $response_data = wp_remote_retrieve_body($response);
    $responseHeaders = wp_remote_retrieve_headers($response)->getAll();

    return array($response_data, $responseHeaders, $respHttpCode);
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
    $this->options[self::TIMEOUT] = $timeout;
  }

  /**
   * Get the maximum request time in seconds.
   * @return timeout in seconds
   */
  public function getTimeout()
  {
    return $this->options[self::TIMEOUT];
  }

  /**
   * Determine whether "Connection Established" quirk is needed
   * @return boolean
   */
  protected function needsQuirk()
  {
      // Stream needs the special quirk
      return true;
  }

  protected function getHttpResponseCode($response_headers)
  {
    $header_count = count($response_headers);

    for ($i = 0; $i < $header_count; $i++) {
      $header = $response_headers[$i];
      if (strncasecmp("HTTP", $header, strlen("HTTP")) == 0) {
        $response = explode(' ', $header);
        return $response[1];
      }
    }
    return self::UNKNOWN_CODE;
  }
}
