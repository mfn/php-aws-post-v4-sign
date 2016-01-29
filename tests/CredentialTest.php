<?php namespace Mfn\Aws\Signature\V4\Tests;

/*
 * This file is part of https://github.com/mfn/php-argument-validation
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use Mfn\Aws\Signature\V4\Credential;

class CredentialTest extends \PHPUnit_Framework_TestCase
{
    const AWS_KEY = '1234';
    const AWS_SECRET = '5678';

    public function testCredential()
    {
        $parts = [
            static::AWS_KEY,
            '20160101',
            'us-west-1',
            's3',
            'aws4_request',
        ];

        $credentialString = join('/', $parts);
        $credential = new Credential($credentialString);

        $this->assertSame($parts[0], $credential->getKey());
        $this->assertSame($parts[1], $credential->getDate());
        $this->assertSame($parts[2], $credential->getRegion());
        $this->assertSame($parts[3], $credential->getService());
        $this->assertSame($parts[4], $credential->getRequestType());
    }

    /**
     * @expectedException \Mfn\Aws\Signature\V4\Exception
     * @expectedExceptionMessage The credential requires to contain exactly 5 parts, separated by "/"
     */
    public function testEmptyCredentialString()
    {
        new Credential('');
    }
}
