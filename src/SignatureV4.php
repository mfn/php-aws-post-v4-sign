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
 * Generate a signature given the secret and a POST request policy.
 *
 * The policy *must contain* the 'x-amz-credential' which contains other
 * necessary information for generating the signature:
 * - access key
 * - date
 * - region
 * - service
 * - request type
 *
 * For more information:
 * - http://docs.aws.amazon.com/AmazonS3/latest/dev/UsingHTTPPOST.html
 * - http://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-UsingHTTPPOST.html
 */
class SignatureV4
{
    /**
     * @var Credential
     */
    protected $credential;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var array
     */
    protected $policy;

    /**
     * @param string $secret
     * @param array $policy
     */
    public function __construct($secret, array $policy)
    {
        $crendential = $this->getCredentialFromPolicy($policy);
        $this->secret = $secret;
        $this->credential = new Credential($crendential);
        $this->policy = $policy;
    }

    /**
     * @param array $policy
     * @return string
     * @throws Exception
     */
    protected function getCredentialFromPolicy(array $policy)
    {
        if (!isset($policy['conditions'])) {
            throw new Exception('No "conditions" key found in policy');
        }

        foreach ($policy['conditions'] as $condition) {
            if (is_array($condition) && isset($condition['x-amz-credential'])) {
                return $condition['x-amz-credential'];
            }
        }

        throw new Exception('No "conditions.x-amz-credential" key found in policy');
    }

    /**
     * @return string
     */
    public function getBase64EncodedPolicy()
    {
        return base64_encode(json_encode($this->policy));
    }

    /**
     * @return string
     */
    public function generateSignature()
    {
        $dateKey = hash_hmac('sha256', $this->credential->getDate(), 'AWS4' . $this->secret, true);
        $dateRegionKey = hash_hmac('sha256', $this->credential->getRegion(), $dateKey, true);
        $dateRegionServiceKey = hash_hmac('sha256', $this->credential->getService(), $dateRegionKey, true);
        $signingKey = hash_hmac('sha256', $this->credential->getRequestType(), $dateRegionServiceKey, true);
        $signature = hash_hmac('sha256', $this->getBase64EncodedPolicy(), $signingKey);

        return $signature;
    }
}
