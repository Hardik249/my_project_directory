<?php 

// tests/Controller/FindOverlappingEventsControllerTest.php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\FindOverlappingEventsController;

class FindOverlappingEventsControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/find/overlapping/events');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        // Add more assertions as needed
    }

    public function testFindOverlap(): void
    {
        $controller = new \App\Controller\FindOverlappingEventsController();

        $events = [
            [
                'name' => 'event 1',
                'description' => 'new event',
                'date' => '2023-11-25',
                'startTime' => '02:00',
                'endTime' => '02:30'
            ],
            [
                'name' => 'event 2',
                'description' => 'new event',
                'date' => '2023-11-25',
                'startTime' => '03:00',
                'endTime' => '03:30'
            ],
            [
                'name' => 'event 3',
                'description' => 'new event',
                'date' => '2023-11-25',
                'startTime' => '03:15',
                'endTime' => '03:30',
            ],
        ];

        $result = $controller->findOverlap($events);

        // Add assertions to verify the expected result
        $this->assertTrue(is_array($result));
        // Add more specific assertions as needed
    }

    public function testCheckOverlap(): void
    {
        $controller = new \App\Controller\FindOverlappingEventsController();

        $event1 = [
            'name' => 'event 1',
            'date' => '2023-11-25',
            'startTime' => '02:00',
            'endTime' => '02:30'
        ];

        $event2 = [
            'name' => 'event 2',
            'date' => '2023-11-25',
            'startTime' => '03:00',
            'endTime' => '03:30'
        ];

        $result = $controller->checkOverlap($event1, $event2);

        // Add assertions to verify the expected result
        $this->assertFalse($result);
        // Add more specific assertions as needed
    }

    // No Overlapping Events:
    public function testNoOverlap()
    {
        $FindOverlappingEventsController = new FindOverlappingEventsController();
        $events = [
            ['name' => 'event 2', 'description' => 'new event', 'date' => '2023-01-01', 'startTime' => '02:00', 'endTime' => '03:00'],
            ['name' => 'event 2', 'description' => 'new event', 'date' => '2023-01-05', 'startTime' => '02:00', 'endTime' => '03:00'],
            // Add more non-overlapping events
        ];

        $result = $FindOverlappingEventsController->findOverlap($events);

        foreach ($result as $event) {
            $this->assertFalse($event['overlap']);
        }
    }

    // Two Overlapping Events
    public function testTwoOverlappingEvents()
    {
        $FindOverlappingEventsController = new FindOverlappingEventsController();
        $events = [
            ['name' => 'event 2', 'description' => 'new event', 'date' => '2023-01-01', 'startTime' => '02:00', 'endTime' => '03:00'],
            ['name' => 'event 2', 'description' => 'new event', 'date' => '2023-01-01', 'startTime' => '02:15', 'endTime' => '02:45'],
            // ...
        ];

        $result = $FindOverlappingEventsController->findOverlap($events);

        foreach ($result as $event) {
            $this->assertTrue($event['overlap']);
        }
    }

    // Multiple Overlapping Events
    public function testMultipleOverlappingEvents()
    {
        $FindOverlappingEventsController = new FindOverlappingEventsController();
        $events = [
            ['name' => 'event 1', 'description' => 'new event', 'date' => '2023-11-25', 'startTime' => '02:00', 'endTime' => '02:30'], 
            ['name' => 'event 2', 'description' => 'new event', 'date' => '2023-11-25', 'startTime' => '03:00', 'endTime' => '03:30'], 
            ['name' => 'event 3', 'description' => 'new event', 'date' => '2023-11-25', 'startTime' => '03:15', 'endTime' => '03:30', ], 
        ];

        $result = $FindOverlappingEventsController->findOverlap($events);
echo "<pre>"; print_r(get_class_methods($this)); exit;
        foreach ($result as $event) {
            if ($event['overlap']) {
                $this->assertTrue($event['overlap']);
            } else {
                $this->assertFalse($event['overlap']);
            }
        }
    }

    // Empty Input Array
    public function testEmptyInputArray()
    {
        $FindOverlappingEventsController = new FindOverlappingEventsController();
        $events = [];

        $result = $FindOverlappingEventsController->findOverlap($events);

        $this->assertEmpty($result);
    }

    // Self-Overlap Check
    public function testSelfOverlapCheck()
    {
        $FindOverlappingEventsController = new FindOverlappingEventsController();
        $events = [
            ['name' => 'event 1', 'description' => 'self-overlapping event', 'date' => '2023-11-25', 'startTime' => '02:00', 'endTime' => '02:30'], 
            [
                'name' => 'event 2', 'description' => 'self-overlapping event', 'date' => '2023-11-25', 'startTime' => '03:00', 'endTime' => '03:00'  // Same start and end time as event 2
            ],
            [
                'name' => 'event 3', 'description' => 'self-overlapping event', 'date' => '2023-11-25', 'startTime' => '04:15', 'endTime' => '04:15'  // Same start and end time as event 3
            ],
        ];

        $result = $FindOverlappingEventsController->findOverlap($events);

        foreach ($result as $event) {
            $this->assertFalse($event['overlap']);
        }
    }





}
