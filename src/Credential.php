<?php namespace Mfn\Aws\Signature\V4;

/*
 * This file is part of https://github.com/mfn/php-aws-post-v4-sign
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

/**
 * Pass a credential as a string and access it's fields.
 *
 * For the details please see:
 * - http://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-HTTPPOSTConstructPolicy.html
 *
 * Specifically the part about "x-amz-credential"
 */
class Credential
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $requestType;

    /**
     * @param string $credential Expects an array with 5 elements
     *   - your AWS access key ID (*not* the secret!)
     *   - the date in UTC YYYYMMDD format
     *   - the region (e.g. 'us-west-1')
     *   - the service (e.g. 's3')
     *   - the request type ('aws4_request')
     * @throws Exception
     */
    public function __construct($credential)
    {
        $parts = explode('/', $credential);

        if (5 !== count($parts)) {
            throw new Exception(
                'The credential requires to contain exactly 5 parts, ' .
                'separated by "/"');
        }

        list(
            $this->key,
            $this->date,
            $this->region,
            $this->service,
            $this->requestType
            ) = $parts;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }
}
