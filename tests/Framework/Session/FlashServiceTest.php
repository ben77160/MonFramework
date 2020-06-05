<?php
namespace Tests\Framework\Session;

use Framework\Session\ArraySession;
use Framework\Session\FlashService;
use PHPUnit\Framework\TestCase;

class FlashServiceTest extends TestCase
{

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

    public function testReturnNullIfNoFlash()
    {
        $this->assertNull($this->flashService->get('success'));
    }

    public function testDeleteFlashAfterGettingIt()
    {
        $methods = ['success', 'error'];
        foreach ($methods as $method) {
            $this->flashService = new FlashService($this->session);
            $this->flashService->$method('Bravo');
            $this->assertEquals('Bravo', $this->flashService->get($method));
            $this->assertNull($this->session->get('flash'));
            $this->assertEquals('Bravo', $this->flashService->get($method));
            $this->assertEquals('Bravo', $this->flashService->get($method));
        }
    }
}
