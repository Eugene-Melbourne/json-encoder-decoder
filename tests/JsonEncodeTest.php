<?php

namespace Tests;

use Exception;
use JsonEncoderDecoderLocal\JsonEncoderDecoder;
use JsonException;
use PHPUnit\Framework\TestCase as BaseTestCase;
use stdClass;

class JsonEncodeTest extends BaseTestCase
{


    public function test_encode(): void
    {
        $val  = null;
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame(null, $json);

        $val  = null;
        $json = (new JsonEncoderDecoder())
            ->setConvertEmptyStringToNull(false)
            ->json_encode($val);
        $this->assertSame('null', $json);

        $val  = '';
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame(null, $json);

        $val  = ' ';
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame('" "', $json);

        $val  = new stdClass();
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame(null, $json);

        $val    = new stdClass();
        $val->a = 'b';
        $json   = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame('{"a":"b"}', $json);

        $val  = [];
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame(null, $json);

        $val  = ['c' => 'd'];
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame('{"c":"d"}', $json);

        $val  = [1, 'one', 'я',];
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame('[1,"one","\u044f"]', $json);

        $val  = [html_entity_decode('&nbsp;'),];
        $json = (new JsonEncoderDecoder())->json_encode($val);
        $this->assertSame('["\u00a0"]', $json);


        try {
            $ex   = null;
            $val  = [chr(160),];
            $json = (new JsonEncoderDecoder())->json_encode($val);
        } catch (Exception $ex) {
            
        }
        $this->assertNotNull($ex);
        $this->assertSame(JsonException::class, get_class($ex));
        $this->assertSame('Malformed UTF-8 characters, possibly incorrectly encoded', $ex->getMessage());
        $this->assertSame(JSON_ERROR_UTF8, $ex->getCode());


        $val  = [chr(160),];
        $json = (new JsonEncoderDecoder())
            ->addOption(JSON_INVALID_UTF8_SUBSTITUTE)
            ->json_encode($val);
        $this->assertSame('["\ufffd"]', $json);

        $val  = [chr(160),];
        $json = (new JsonEncoderDecoder())
            ->addOption(JSON_INVALID_UTF8_IGNORE)
            ->json_encode($val);
        $this->assertSame('[""]', $json);
    }


    public function test_decode(): void
    {
        $json = null;
        $val  = (new JsonEncoderDecoder())->json_decode($json);
        $this->assertSame(null, $val);

        $json = 'null';
        $val  = (new JsonEncoderDecoder())->json_decode($json);
        $this->assertSame(null, $val);

        $json = '';
        $val  = (new JsonEncoderDecoder())->json_decode($json);
        $this->assertSame(null, $val);

        $json = '[]';
        $val  = (new JsonEncoderDecoder())->json_decode($json);
        $this->assertSame(null, $val);

        $json = '{}';
        $val  = (new JsonEncoderDecoder())->json_decode($json);
        $this->assertSame(null, $val);

        $json = '{"c":"d"}';
        $val  = (new JsonEncoderDecoder())->json_decode($json);
        $this->assertSame(['c' => 'd'], $val);

        $json = '{"c":"d"}';
        $val  = (new JsonEncoderDecoder())->setReturnAsAssociative(false)->json_decode($json);
        $this->assertSame(stdClass::class, get_class($val));
        $this->assertSame('d', $val->c);


        try {
            $ex   = null;
            $json = 's';
            $val  = (new JsonEncoderDecoder())->json_decode($json);
        } catch (Exception $ex) {
            
        }
        $this->assertNotNull($ex);
        $this->assertSame(JsonException::class, get_class($ex));
        $this->assertSame('Syntax error', $ex->getMessage());
        $this->assertSame(JSON_ERROR_SYNTAX, $ex->getCode());


        try {
            $ex   = null;
            $json = '"' . chr(160) . '"';
            $val  = (new JsonEncoderDecoder())->json_decode($json);
        } catch (Exception $ex) {
            
        }
        $this->assertNotNull($ex);
        $this->assertSame(JsonException::class, get_class($ex));
        $this->assertSame('Malformed UTF-8 characters, possibly incorrectly encoded', $ex->getMessage());
        $this->assertSame(JSON_ERROR_UTF8, $ex->getCode());
    }


}
