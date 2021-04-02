<?php

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class DiagramTest extends TestCase {

  public function setUp(): void
  {
    $this->connection = new Connection();
  }
}
