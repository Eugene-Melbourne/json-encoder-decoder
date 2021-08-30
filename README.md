# JsonEncoderDecoder

Encodes and Decodes JSON strings

Installation

    $ composer require  eugene-melbourne/json-encoder-decoder

Most importantly it has default parameters pre-set

- JSON_THROW_ON_ERROR
- convertEmptyStringToNull
- ReturnAsAssociative

It can be changed though.

Also, it can ConvertEmptyStringToNull and has unit tests to see it in action.

Example:

    // Example 1
    $val = (new JsonEncoderDecoder())->json_encode(null)
    // $val = null   
    
    // Example 2
    $val  = [chr(160),];
    $json = (new JsonEncoderDecoder())->json_encode($val);
    // throws JsonException
    
    // Example 3
    $val  = [chr(160),];
    $json = (new JsonEncoderDecoder())
            ->addOption(JSON_INVALID_UTF8_SUBSTITUTE)
            ->json_encode($val);
    // $json = '["\ufffd"]';

See more examples in unit tests.

P.S.
The char code `160` would be `&nbsp;`