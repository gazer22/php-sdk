<?php
/**
 * Copyright 2017 Smartwaiver
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace Smartwaiver\Tests;

use GuzzleHttp\Psr7\Response;
use Smartwaiver\Exceptions\SmartwaiverHTTPException;
use Smartwaiver\Exceptions\SmartwaiverSDKException;

/**
 * Class SmartwaiverExceptionTest
 *
 * @package Smartwaiver\Tests
 */
class SmartwaiverExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that an HTTP Exception allows access to all the correct information
     */
    public function testHTTPExceptionCreatedProperly()
    {
        $content = [
            'version' => 4,
            'id' => 'id',
            'ts' => 'ts',
            'type' => 'error',
            'message' => 'Error Message',
        ];

        $swException = new SmartwaiverHTTPException(new Response(404), 'Body Content', $content);
        $responseInfo = $swException->getResponseInfo();

        $this->assertEquals('Error Message', $swException->getMessage());
        $this->assertEquals(404, $swException->getCode());
        $this->assertEquals('Body Content', $swException->getGuzzleBody());
        $this->assertEquals(404, $swException->getGuzzleResponse()->getStatusCode());

        // Everything but the message should get returned
        unset($content['message']);
        $this->assertEquals($content, $responseInfo);
    }

    /**
     * Test that an SDK exception allows access to the correct information
     */
    public function testSDKExceptionCreatedProperly()
    {
        $swException = new SmartwaiverSDKException(new Response(404), 'Body Content', 'Error Message');

        $this->assertEquals(0, $swException->getCode());
        $this->assertEquals('Error Message', $swException->getMessage());
        $this->assertEquals('Body Content', $swException->getGuzzleBody());
        $this->assertEquals(404, $swException->getGuzzleResponse()->getStatusCode());
    }
}