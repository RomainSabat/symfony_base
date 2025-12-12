<?php

namespace App\Tests;

use App\DatabaseGateway;
use App\DatabaseMapper;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseMapperTest extends TestCase
{
    /**
     * Test avec une vraie base SQLite
     */
    public function testRealDatabase()
    {
        // 1️⃣ Création d'une vraie base sqlite en mémoire
        $pdo = new PDO('sqlite::memory:');

        // 2️⃣ Création d'une table pour le test
        $pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT);");
        $pdo->exec("CREATE TABLE posts (id INTEGER PRIMARY KEY, title TEXT);");

        // 3️⃣ Intégration avec les classes réelles
        $gateway = new DatabaseGateway($pdo);
        $mapper = new DatabaseMapper($gateway);

        $tables = $mapper->findAll();

        // 4️⃣ Vérifications
        $this->assertContains('users', $tables);
        $this->assertContains('posts', $tables);
        $this->assertCount(2, $tables);
    }

    /**
     * Test avec un stub
     */
    public function testStubDatabase()
    {
        // 1️⃣ Création d’un stub
        $stub = $this->createStub(DatabaseGateway::class);

        // 2️⃣ Simulation du retour
        $stub->method('listDbs')->willReturn(['db1', 'db2', 'db3']);

        // 3️⃣ Mapper utilisant le stub
        $mapper = new DatabaseMapper($stub);

        $databases = $mapper->findAll();

        // 4️⃣ Vérifications
        $this->assertCount(3, $databases);
    }

    /**
     * Test avec un mock
     */
    public function testMockDatabase()
    {
        // 1️⃣ Création du mock
        $mock = $this->createMock(DatabaseGateway::class);

        // 2️⃣ Attente d’un appel et définition du retour
        $mock->expects($this->once())
             ->method('listDbs')
             ->willReturn(['alpha', 'beta']);

        // 3️⃣ Mapper utilisant le mock
        $mapper = new DatabaseMapper($mock);

        $databases = $mapper->findAll();

        // 4️⃣ Vérifications
        $this->assertCount(2, $databases);
    }
}
