<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Utils\DatabaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProcessControllerTest extends DatabaseWebTestCase
{
    public function test_it_creates_process(): void
    {
        $payload = [
            'title' => 'New API Process',
            'description' => 'Created via functional test',
            'status' => 'todo',
        ];

        $this->client->request('POST', '/process', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode($payload));

        $this->assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
    }

        public function test_it_creates_and_fetches_process(): void
    {
        $payload = [
            'title' => 'New API Process',
            'description' => 'Created via functional test',
            'status' => 'todo',
        ];

        // Create the process
        $this->client->request('POST', '/process', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode($payload));
        $this->assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);

        // Read the process
        $this->client->request('GET', '/process/1', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode($payload));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $read = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(1, $read['process'][0]['id']);
    }

    public function test_it_fetches_process_list(): void
    {
        $this->client->request(method: 'GET', uri: '/process', server: ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on']);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function test_it_fetches_process_list_filter(): void
    {
        $payload = [
            'title' => 'New API Process',
            'description' => 'Created via functional test',
            'status' => 'in_progress',
        ];

        // Create the process
        $this->client->request('POST', '/process', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode($payload));
        $this->assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);

        $this->client->request(method: 'GET', uri: '/process', parameters: ['statusFilter' => 'in_progress'], server: ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on']);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function test_it_updates_process_status(): void
    {
        // Create a process first
        $this->client->request('POST', '/process', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode([
            'title' => 'Process to update',
            'description' => 'Status test',
            'status' => 'in_progress',
        ]));

        // Change status to "done"
        $this->client->request('PATCH', "/process/1/status", [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode([
            'status' => 'done',
        ]));
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/process/1', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on']);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $updated = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame('done', $updated['process'][0]['status']);
    }

    public function test_it_creates_and_deletes_process(): void
    {
        $payload = [
            'title' => 'New API Process',
            'description' => 'Created via functional test',
            'status' => 'in_progress',
        ];

        // Create the process
        $this->client->request('POST', '/process', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on'], json_encode($payload));
        $this->assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);

        // Delete the process
        $this->client->request('DELETE', '/process/1', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on']);
        $this->assertResponseIsSuccessful();
    }

    public function test_it_fails_to_delete_process(): void
    {
        // Attempt to delete nonexistent
        $this->client->request('DELETE', '/process/1', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTPS' => 'on']);
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
