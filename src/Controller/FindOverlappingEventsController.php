<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FindOverlappingEventsController extends AbstractController
{
    #[Route('/find/overlapping/events', name: 'app_find_overlapping_events')]
    public function index(): JsonResponse
    {
        $overlappingEvents = [];

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

        $overlappingEvents = $this->findOverlap($events);
// echo "<pre>"; print_r($overlappingEvents); exit;
        return new JsonResponse(['overlapping_events' => $overlappingEvents]);
        // return $overlappingEvents;
    }

    public function findOverlap(array $events): array
    {
        $result = [];

        foreach ($events as $key1 => $event1) {
            foreach ($events as $key2 => $event2) {
                // Skip self-comparison
                if ($key1 === $key2) {
                    continue;
                }

                $overlap = $this->checkOverlap($event1, $event2);

                if ($overlap) {
                    // Add the 'overlap' flag to both events
                    $events[$key1]['overlap'] = true;
                    $events[$key2]['overlap'] = true;
                } else {
                    // Add the 'overlap' flag to both events
                    $events[$key1]['overlap'] = false;
                    $events[$key2]['overlap'] = false;
                }
            }
        }

        return $events;
    }

    public function checkOverlap(array $event1, array $event2): bool
    {
        $start1 = new \DateTime($event1['date'] . ' ' . $event1['startTime']);
        $end1 = new \DateTime($event1['date'] . ' ' . $event1['endTime']);

        $start2 = new \DateTime($event2['date'] . ' ' . $event2['startTime']);
        $end2 = new \DateTime($event2['date'] . ' ' . $event2['endTime']);

        // Check for overlap
        return $start1 < $end2 && $end1 > $start2;
    }
}
