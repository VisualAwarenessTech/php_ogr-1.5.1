<?php

class OGRSFDriverRegistrarTest3 extends PHPUnit_Framework_TestCase
{
    public $strPathToData;
    public $strPathToOutputData;
    public $strDestDataSource;
    public $bUpdate;
    public $hOGRSFDriver;
    public $strCapability;

    // called before the test functions will be executed
    // this function is defined in PHPUnit_Framework_TestCase and overwritten
    // here
    public function setUp()
    {
        $this->strPathToData = "./data/mif";
        $this->strPathToOutputData = create_temp_directory(__CLASS__);
        $this->strDestDataSource = "OutputDS";
        $this->bUpdate = false;
        $this->strCapability = ODrCCreateDataSource;
    }
    // called after the test functions are executed
    // this function is defined in PHPUnit_Framework_TestCase and overwritten
    // here
    public function tearDown()
    {
        delete_directory($this->strPathToOutputData);
        // delete your instance
        unset($this->strPathToData);
        unset($this->strPathToOutputData);
        unset($this->strDestDataSource);
        unset($this->bUpdate);
        unset($this->strCapability);
        unset($this->hOGRSFDriver);
    }

    /***********************************************************************
     *                       testOGRGetDriverCount0()
     *               Registered drivers supposed to be zero.
     ************************************************************************/

    public function testOGRGetDriverCount0()
    {
        $nDriverCount = OGRGetDriverCount();
        printf("driver count = %d\n", $nDriverCount);

        $expected = 0;
        $this->assertEquals(
            $expected,
            $nDriverCount,
            "Problem with OGRGetDriverCount():  Driver count is supposed to be " . $expected,
            0 /*$delta*/
        );
    }

    /***********************************************************************
     *                       testOGRGetDriverCount1()
     *    Adding one driver.  PROBLEM WITH OGRGetDriver when no registered
     *      driver exist.  TO COME BACK TO.
     *
     ************************************************************************/

    public function testOGRGetDriverCount1()
    {
        $hDriver = OGRGetDriver(0);

        $this->assertNull(
            $result,
            "Problem with OGRGetDriver():  Return driver is supposed to be NULLsince no driver is registered."
        );
        $nDriverCount = OGRGetDriverCount();
        printf("driver count = %d\n", $nDriverCount);

        $expected = 0;

        $this->assertEquals(
            $expected,
            $nDriverCount,
            "Problem with OGRGetDriverCount():  Driver count is supposed to be " . $expected . " when no driver is registered.",
            0
        );
    }

    /***********************************************************************
     *                       testOGRGetDriverCount2()
     *    Verify driver count with all drivers registered.
     *
     ************************************************************************/
    public function testOGRGetDriverCount2()
    {
        OGRRegisterAll();

        $nDriverCount = OGRGetDriverCount();
        printf("driver count = %d\n", $nDriverCount);
        $expected = 10;
        printf("in testogrgetdrivercount2a\n");

        $this->assertEquals(
            $expected,
            $nDriverCount,
            "Problem with OGRGetDriverCount():  Driver count is supposed to be " . $expected . " after drivers are registered.",
            0 /*$delta*/
        );
        printf("in testogrgetdrivercount2b\n");
    }

    /***********************************************************************
     *                       testOGRGetDriverCount3()
     *    Verify driver count after registering a new driver.
     *    ERROR TO COME BACK TO.  OGRRegisterDriver seems to have
     *    no utility here.  hOGRSFDriver is not supposed to be null
     *    after calling OGROpen().
     *
     ************************************************************************/

    public function testOGRGetDriverCount3()
    {
        printf("in testogrgetdrivercount3\n");
        $hDS = OGROpen(
            $this->strPathToData,
            $this->bUpdate,
            $this->hOGRSFDriver
        );

        $this->assertNotNull(
            $hDS,
            "Problem with OGROpen():  data source handle is not supposed to be NULL."
        );

        $nDriverCount = OGRGetDriverCount();

        $expected = 0;
        $this->assertEquals(
            $expected,
            $nDriverCount,
            "Problem with OGRGetDriverCount():  Driver count is supposed to be " . $expected . " before registering a new driver.",
            0 /*$delta*/
        );

        if ($this->hOGRSFDriver != null) {
            OGRRegisterDriver($this->hOGRSFDriver);
        }

        $nDriverCount = OGRGetDriverCount();

        $expected = 1;
        $this->assertEquals(
            $expected,
            $nDriverCount,
            "Problem with OGRRegisterDriver():  Driver count is supposed to be " . $expected . " after a new driver is registered.",
            0 /*$delta*/
        );

        OGR_DS_Destroy($hDS);
    }

    /***********************************************************************
     *                       testOGRGetDriver0()
     *       Get a driver handle after execution OGRRegisterAll().
     ************************************************************************/
    public function testOGRGetDriver0()
    {
        OGRRegisterAll();

        $hDriver = OGRGetDriver(0);

        $this->assertNotNull(
            $hDriver,
            "Problem with OGRGetDriver():Driver is not supposed to be NULL"
        );
    }

    /***********************************************************************
     *                       testOGRGetDriver1()
     *               Getting a driver with an id out of range.
     ************************************************************************/

    public function testOGRGetDriver1()
    {
        OGRRegisterAll();

        $hDriver = OGRGetDriver(50);

        $this->assertNull(
            $hDriver,
            "Problem with OGRGetDriver():  driver handle is supposed to be NULL since requested driver is out of range."
        );
    }

    /***********************************************************************
     *                       testOGRRegisterDriver0()
     *               Adding an existing driver has no effect.
     ************************************************************************/

    public function testOGRRegisterDriver0()
    {
        OGRRegisterAll();

        $hDriver = OGRGetDriver(2);

        $this->assertNotNull(
            $hDriver,
            "Problem with OGRGetDriver():  driver handle is not supposed to be NULL."
        );
        OGRRegisterDriver($hDriver);
        $nDriverCount = OGRGetDriverCount();

        $expected = 10;
        $this->assertEquals(
            $expected,
            $nDriverCount,
            "Problem with OGRRegisterDriver():  driver count is supposed to be " . $expected . " since this driver is already registered.",
            0 /*$delta*/
        );
    }
}

?> 
