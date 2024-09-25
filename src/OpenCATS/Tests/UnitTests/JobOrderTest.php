<?php

namespace OpenCATS\Tests\UnitTests;

use OpenCATS\Entity\JobOrder;
use PHPUnit\Framework\TestCase;

class JobOrderTest extends TestCase
{
    public const JOB_ORDER_TITLE = 'Test job order';

    public const COMPANY_ID = 1;

    public const CONTACT_ID = 1;

    public const JOB_ORDER_DESCRIPTION = 'Some description';

    public const JOB_ORDER_NOTES = 'Some note';

    public const JOB_ORDER_DURATION_IN_DAYS = 30;

    public const JOB_ORDER_MAX_RATE = 60000;

    public const JOB_ORDER_TYPE = '';

    public const JOB_ORDER_IS_HOT = 1;

    public const JOB_ORDER_PUBLIC = 1;

    public const JOB_ORDER_OPENINGS = 'Openings';

    public const JOB_ORDER_AVAILABLE_OPENINGS = 'Openings';

    public const COMPANY_JOB_ID = 10;

    public const JOB_ORDER_SALARY = 30000;

    public const CITY = 'Colonia';

    public const STATE = 'MALDONADO';

    public const JOB_ORDER_START_DATE = '2016-05-02';

    public const JOB_ORDER_ENTERED_BY = 31337;

    public const JOB_ORDER_RECRUITER = 31337;

    public const JOB_ORDER_OWNER = null;

    public const DEPARTMENT = 'DepartmentOne';

    public const DEPARTMENT_ID = 1234;

    public const SITE_ID = 1;

    public const JOB_ORDER_QUESTIONNAIRE = 'How do you see yourself in 5 years?';

    public function test_create_CreateAndGetJobOrderTitle_ReturnsName()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_TITLE, $jobOrder->getTitle());
    }

    public function test_create_CreateAndGetCompanyJobId_ReturnsCompanyJobId()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::COMPANY_JOB_ID, $jobOrder->getCompanyJobId());
    }

    public function test_create_CreateAndGetCompanyId_ReturnsCompanyId()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::COMPANY_ID, $jobOrder->getCompanyId());
    }

    public function test_create_CreateAndGetContactId_ReturnsContactId()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::CONTACT_ID, $jobOrder->getContactId());
    }

    public function test_create_CreateAndGetDescription_ReturnsDescription()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_DESCRIPTION, $jobOrder->getDescription());
    }

    public function test_create_CreateAndGetNotes_ReturnsNotes()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_NOTES, $jobOrder->getNotes());
    }

    public function test_create_CreateAndGetDuration_ReturnsDuration()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_DURATION_IN_DAYS, $jobOrder->getDuration());
    }

    public function test_create_CreateAndGetMaxRate_ReturnsMaxRate()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_MAX_RATE, $jobOrder->getMaxRate());
    }

    public function test_create_CreateAndGetType_ReturnsType()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_TYPE, $jobOrder->getType());
    }

    public function test_create_CreateAndGetIsHot_ReturnsIsHot()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_IS_HOT, $jobOrder->isHot());
    }

    public function test_create_CreateAndGetIsPublic_ReturnsIsPublic()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_PUBLIC, $jobOrder->isPublic());
    }

    public function test_create_CreateAndGetOpenings_ReturnsOpenings()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_OPENINGS, $jobOrder->getOpenings());
    }

    public function test_create_CreateAndGetAvailableOpenings_ReturnsAvailableOpenings()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_AVAILABLE_OPENINGS, $jobOrder->getAvailableOpenings());
    }

    public function test_create_CreateAndGetSalary_ReturnsSalary()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_SALARY, $jobOrder->getSalary());
    }

    public function test_create_CreateAndGetCity_ReturnsCity()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::CITY, $jobOrder->getCity());
    }

    public function test_create_CreateAndGetState_ReturnsState()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::STATE, $jobOrder->getState());
    }

    public function test_create_CreateAndGetDepartmentId_ReturnsDepartmentId()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::DEPARTMENT_ID, $jobOrder->getDepartmentId());
    }

    public function test_create_CreateAndGetStartDate_ReturnsStartDate()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(null, $jobOrder->getStartDate());
    }

    public function test_create_CreateAndGetEnteredBy_ReturnsEnteredBy()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_ENTERED_BY, $jobOrder->getEnteredBy());
    }

    public function test_create_CreateAndGetRecruiter_ReturnsRecruiter()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_RECRUITER, $jobOrder->getRecruiter());
    }

    public function test_create_CreateAndGetOwner_ReturnsOwner()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_OWNER, $jobOrder->getOwner());
    }

    public function test_create_CreateAndGetSiteId_ReturnsSiteId()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::SITE_ID, $jobOrder->getSiteId());
    }

    public function test_create_CreateAndGetQuestionnaireReturnsQuestionnaire()
    {
        $jobOrder = $this->createJobOrder();
        $this->assertEquals(self::JOB_ORDER_QUESTIONNAIRE, $jobOrder->getQuestionnaire());
    }

    private function createJobOrder()
    {
        return JobOrder::create(
            self::SITE_ID,
            self::JOB_ORDER_TITLE,
            self::COMPANY_ID,
            self::CONTACT_ID,
            self::JOB_ORDER_DESCRIPTION,
            self::JOB_ORDER_NOTES,
            self::JOB_ORDER_DURATION_IN_DAYS,
            self::JOB_ORDER_MAX_RATE,
            self::JOB_ORDER_TYPE,
            self::JOB_ORDER_IS_HOT,
            self::JOB_ORDER_PUBLIC,
            self::JOB_ORDER_OPENINGS,
            self::COMPANY_JOB_ID,
            self::JOB_ORDER_SALARY,
            self::CITY,
            self::STATE,
            self::JOB_ORDER_START_DATE,
            self::JOB_ORDER_ENTERED_BY,
            self::JOB_ORDER_RECRUITER,
            self::JOB_ORDER_OWNER,
            self::DEPARTMENT_ID,
            self::JOB_ORDER_QUESTIONNAIRE
        );
    }
}
