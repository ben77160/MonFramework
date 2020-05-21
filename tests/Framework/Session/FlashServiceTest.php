<?php
namespace Tests\Framework\Session;

use Framework\Session\ArraySession;
use Framework\Session\FlashService;
use PHPUnit\Framework\TestCase;

class FlashServiceTest extends TestCase {

    /**
     * @var ArraySession
     */
    private $session;

    /**
     * @var FlashService
     */
    private $flashService;

    public function setUp()
    {
        $this->session = new ArraySession();
        $this->flashService = new FlashService($this->session);
    }

    public function testDeleteFlashAfterGettingIt() {
        $this->flashService->success('Bravo');
        $this->assertEquals('Bravo', $this->flashService->get('success'));
        $this->assertNull($this->session->get('flash'));
        $this->assertEquals('Bravo', $this->flashService->get('success'));
        $this->assertEquals('Bravo', $this->flashService->get('success'));
    }

}