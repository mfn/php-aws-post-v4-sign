# Create V4 signature for AWS S3 REST API Post requests [ ![Travis Build Status](https://travis-ci.org/mfn/php-aws-post-v4-sign.svg?branch=master)](https://travis-ci.org/mfn/php-aws-post-v4-sign)

Homepage: https://github.com/mfn/php-aws-post-v4-sign

# Blurb

This library can create signature for a POST request designated for Amazon
Simple Storage (S3) REST API service.

This package does not depend on the AWS PHP SDK. It can only create a signature.
Logically, this code exists in similar form inside the SDK, but it's not
possible form the outside to leaverage it.

Fore more information see:
- http://docs.aws.amazon.com/AmazonS3/latest/dev/UsingHTTPPOST.html
- http://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-UsingHTTPPOST.html

# Requirements

PHP 5.4

# Install / Setup

Using composer: `composer.phar require mfn/aws-post-v4-sign 0.1`


# Example

```PHP
<?php
use Mfn\Aws\Signature\V4\SignatureV4;

$secret = 'ADFSDFDS3432S23423'; # Your secret key => never share!
$time = time();
$credential = join('/', [
    $accesKeyId, # Your AWS access key ID
    gmdate('Ymd', $time),
    'us-west-1',
    's3',
    'aws4_request',
]);
# Example policy
$policy = [
    'expiration' => gmdate('Y-m-d\TH:i:s\Z', strtotime('+2 hours', $time)),
    'conditions' => [
        ['acl' => 'public-read'],
        ['bucket' => 'your-bucket'],
        ['starts-with', '$key', ''],
        ['x-amz-credential' => $credential],
        ['x-amz-algorithm' => 'AWS4-HMAC-SHA256'],
        ['x-amz-date' => gmdate('Ymd\THis\Z', $time)],
    ],
];

$sigGen = new SignatureV4(
    $secret, # the secret part; never share with anyone!
    $policy
);
$signature = $sigGen->getSignature();
```

The `SignatureV4` class will parse the `$policy` to that extent to extract
the `x-awz-credential` key, as this credential is an essential part for
creating the signature.

As a base64 encoded JSON representation of the `$policy` is required to
create the signature and is usually required for the final request,
a method for retrieving it exists:
`$encodedPolicy = $sigGen->getBase64EncodedPolicy();`

# Contribute

Fork it, hack on a feature branch, create a pull request, be awesome!

No developer is an island so adhere to these standards:

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)

© Markus Fischer <markus@fischer.name>
