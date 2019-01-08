<?php

class OGRFeatureTest3 extends PHPUnit_Framework_TestCase
{
    public $strPathToOutputData;
    public $strTmpDumpFile;
    public $strPathToStandardData;
    public $strPathToData;
    public $bUpdate;
    public $hDestDS;
    public $hDestLayer;
    public $strDestDataSource;
    public $hOGRSFDriver;
    public $astrOptions;

    public function setUp()
    {
        $this->strPathToData = "./data/mif";
        $this->strPathToStandardData = "./data/testcase/";
        $this->strPathToOutputData = create_temp_directory(__CLASS__);
        $this->strTmpDumpFile = "DumpFile.tmp";
        $this->bUpdate = false;
        $this->strDestDataSource = "OutputDS.tab";

        $iDriver = 5;
        $this->hOGRSFDriver = OGRGetDriver($iDriver);
        $this->astrOptions = null;
        $this->hDestDS = OGR_Dr_CreateDataSource(
            $this->hOGRSFDriver,
            $this->strPathToOutputData . $this->strDestDataSource,
            $this->astrOptions
        );

        if ($this->hDestDS == null) {
            printf("Unable to create destination data source\n");
            return false;
        }
        $iLayer = 0;

        $this->hDestLayer = OGR_DS_GetLayer($this->hDestDS, $iLayer);
    }

    public function tearDown()
    {
        delete_directory($this->strPathToOutputData);
        OGR_DS_Destroy($this->hDestDS);
        unset($this->strPathToData);
        unset($this->strPathToStandardData);
        unset($this->strPathToOutputData);
        unset($this->strTmpDumpFile);
        unset($this->bUpdate);
        unset($this->strDestDataSource);
        unset($this->hDestLayer);
        unset($this->hDestDS);
        unset($this->strDirName);
        unset($this->OGRSFDriver);
        unset($this->astrOptions);
    }

    /***********************************************************************
     *                            testOGR_F_SetGetFieldInteger()
     ************************************************************************/

    public function testOGR_F_SetGetFieldInteger()
    {
        $iValueIn = 4312;
        $hFieldDefn = OGR_Fld_Create("id", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );

        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFeatureDefn = OGR_L_GetLayerDefn($this->hDestLayer);
        $hF = OGR_F_Create($hFeatureDefn);

        $iFieldIndex = 0;
        OGR_F_SetFieldInteger($hF, $iFieldIndex, $iValueIn);

        $iValueOut = OGR_F_GetFieldAsInteger($hF, $iFieldIndex);
        $expected = $iValueIn;
        $this->AssertEquals(
            $expected,
            $iValueOut,
            "Problem with OGR_F_SetFieldInteger() or OGR_F_GetFieldAsInteger()."
        );

        OGR_F_Destroy($hF);
    }

    /***********************************************************************
     *                            testOGR_F_SetGetFieldDouble()
     ************************************************************************/

    public function testOGR_F_SetGetFieldDouble()
    {
        $dfValueIn = 6533.58;
        $hFieldDefn = OGR_Fld_Create("area", OFTReal);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );

        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFeatureDefn = OGR_L_GetLayerDefn($this->hDestLayer);
        $hF = OGR_F_Create($hFeatureDefn);

        $iFieldIndex = 0;
        OGR_F_SetFieldDouble($hF, $iFieldIndex, $dfValueIn);

        $dfValueOut = OGR_F_GetFieldAsDouble($hF, $iFieldIndex);
        $expected = $dfValueIn;
        $this->AssertEquals(
            $expected,
            $dfValueOut,
            "Problem with OGR_F_SetFieldDouble() or OGR_F_GetFieldAsDouble()."
        );

        OGR_F_Destroy($hF);
    }

    /***********************************************************************
     *                            testOGR_F_SetGetFieldString()
     ************************************************************************/

    public function testOGR_F_SetGetFieldString()
    {
        $strValueIn = "Liberty";
        $hFieldDefn = OGR_Fld_Create("name", OFTString);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );

        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFeatureDefn = OGR_L_GetLayerDefn($this->hDestLayer);
        $hF = OGR_F_Create($hFeatureDefn);

        $iFieldIndex = 0;
        OGR_F_SetFieldString($hF, $iFieldIndex, $strValueIn);

        $strValueOut = OGR_F_GetFieldAsInteger($hF, $iFieldIndex);
        $expected = $strValueIn;

        $this->AssertEquals(
            $expected,
            $strValueOut,
            "Problem with OGR_F_SetFieldString() or OGR_F_GetFieldAsString()."
        );

        OGR_F_Destroy($hF);
    }

    /***********************************************************************
     *                            testOGR_F_SetGetFID()
     ************************************************************************/

    public function testOGR_F_SetGetFID()
    {
        $iValueIn = 4312;
        $hFieldDefn = OGR_Fld_Create("id", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );

        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFeatureDefn = OGR_L_GetLayerDefn($this->hDestLayer);
        $hF = OGR_F_Create($hFeatureDefn);

        $nFID = OGR_F_GetFID($hF);
        $expected = 1;  /*Now equals -1 when DS not closed.*/
        $this->AssertEquals($expected, $nFID, "Problem with first OGR_F_GetFID().");

        $nFID = 2;
        $eErr = OGR_F_SetFID($hF, $nFID);
        $nFID = OGR_F_GetFID($hF);
        $expected = 2;
        $this->AssertEquals(
            $expected,
            $nFID,
            "Problem with OGR_F_SetFID() OGR_F_GetFID()."
        );

        OGR_F_Destroy($hF);
    }

    /***********************************************************************
     *                            testOGR_F_SetFrom()
     ************************************************************************/

    public function testOGR_F_SetFrom()
    {
        $strStandardFile = "testOGR_F_SetFrom";
        $hFieldDefn = OGR_Fld_Create("FNODE_", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );

        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("TNODE_", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("LPOLY_", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("RPOLY_", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("LENGTH", OFTReal);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("GRID_", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("GRID_ID", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("F_CODE", OFTInteger);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFieldDefn = OGR_Fld_Create("F_TYPE", OFTString);
        $eErr = OGR_L_CreateField(
            $this->hDestLayer,
            $hFieldDefn,
            0 /*bApproxOk*/
        );
        if ($eErr != OGRERR_NONE) {
            printf("Error creating field.\n");
            return false;
        }

        $hFeatureDefn = OGR_L_GetLayerDefn($this->hDestLayer);
        $hDestF = OGR_F_Create($hFeatureDefn);

        $hDriver = null;
        $hSrcDS = OGROpen($this->strPathToData, $this->bUpdate, $hDriver);

        $iLayer = 3;
        $hSrcLayer = OGR_DS_GetLayer($hSrcDS, $iLayer);
        $nFID = 5;
        $hSrcF = OGR_L_GetFeature($hSrcLayer, $nFID);

        $bForgiving = true;
        $eErr = OGR_F_SetFrom($hDestF, $hSrcF, $bForgiving);

        $fpOut = fopen(
            $this->strPathToOutputData . $this->strTmpDumpFile,
            "w"
        );

        if ($fpOut == false) {
            printf("Dump file creation error\n");
            return false;
        }

        OGR_F_DumpReadable($fpOut, $hDestF);

        OGR_F_Destroy($hSrcF);
        OGR_F_Destroy($hDestF);

        OGR_DS_Destroy($hSrcDS);

        fclose($fpOut);

        system(
            "diff --brief " . $this->strPathToOutputData . $this->strTmpDumpFile . " " . $this->strPathToStandardData . $strStandardFile,
            $iReval
        );

        $this->assertFalse(
            $iRetval,
            "Problem with OGR_F_SetFrom() Files comparison did not matched.\n"
        );
    }
}
