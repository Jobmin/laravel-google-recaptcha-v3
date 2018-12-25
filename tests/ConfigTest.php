<?php

namespace TimeHunter\Tests;

use PHPUnit\Framework\TestCase;
use TimeHunter\LaravelGoogleCaptchaV3\Configurations\ReCaptchaConfigV3;
use TimeHunter\LaravelGoogleCaptchaV3\Core\GoogleReCaptchaV3Response;
use TimeHunter\LaravelGoogleCaptchaV3\Core\GuzzleRequestClient;
use TimeHunter\LaravelGoogleCaptchaV3\GoogleReCaptchaV3;



class ConfigTest extends TestCase
{

    public function testServiceDisabled()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(false);

        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn(false);


        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse('test');
        $this->assertEquals(true, $response->isSuccess());
    }


    public function testMissingInput()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);

        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn(false);


        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse(null);
        $this->assertEquals(false, $response->isSuccess());

        $response = $service->verifyResponse('');

        $this->assertEquals(false, $response->isSuccess());
    }


    public function testEmptyResponse()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = null;


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse(null);
        $this->assertEquals(false, $response->isSuccess());

    }


    public function testFalseResponse()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = '{ "success": false, "challenge_ts": "2018-12-25T03:35:32Z", "hostname": "ryandeng.test", "score": 0.9, "action": "contact_us" }';


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse('test response');
        $this->assertEquals(false, $response->isSuccess());
    }




    public function testHostName1()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = '{ "success": true, "challenge_ts": "2018-12-25T03:35:32Z", "hostname": "ryandeng.test", "score": 0.9, "action": "contact_us" }';

        $configStub->method('getHostName')
            ->willReturn('wrong.test');


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse('test response');

        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals(GoogleReCaptchaV3Response::ERROR_HOSTNAME, $response->getMessage());
    }



    public function testHostName2()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = '{ "success": true, "challenge_ts": "2018-12-25T03:35:32Z", "hostname": "ryandeng.test", "score": 0.9, "action": "contact_us" }';

        $configStub->method('getHostName')
            ->willReturn('');


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse('test response');

        $this->assertEquals(true, $response->isSuccess());
    }


    public function testAction()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = '{ "success": true, "challenge_ts": "2018-12-25T03:35:32Z", "hostname": "ryandeng.test", "score": 0.9, "action": "contact_us" }';


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);
        $service->setAction('contact_us_wrong');

        $response = $service->verifyResponse('test response');
        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals(GoogleReCaptchaV3Response::ERROR_ACTION, $response->getMessage());
    }


    public function testActionRight()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = '{ "success": true, "challenge_ts": "2018-12-25T03:35:32Z", "hostname": "ryandeng.test", "score": 0.9, "action": "contact_us" }';


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);
        $service->setAction('contact_us');

        $response = $service->verifyResponse('test response');
        $this->assertEquals(true, $response->isSuccess());
    }


    public function testActionSkip()
    {
        // Create a stub for the SomeClass class.
        $configStub = $this->createMock(ReCaptchaConfigV3::class);

        // Configure the stub.
        $configStub->method('isServiceEnabled')
            ->willReturn(true);


        $testJson = '{ "success": true, "challenge_ts": "2018-12-25T03:35:32Z", "hostname": "ryandeng.test", "score": 0.9, "action": "contact_us" }';


        $clientStub = $this->createMock(GuzzleRequestClient::class);
        $clientStub->method('post')
            ->willReturn($testJson);

        $service = new GoogleReCaptchaV3($configStub, $clientStub);

        $response = $service->verifyResponse('test response');
        $this->assertEquals(true, $response->isSuccess());
    }



}