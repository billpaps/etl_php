<?php

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

interface DatabaseWorker{
    public function save($data);
}

class Sqlite implements DatabaseWorker{

    private EntityManager $entityManager;
    private int $batchSize;

    function __construct(EntityManager $entityManager, int $batchSize){
        $this->entityManager = $entityManager;
        $this->batchSize = $batchSize;
    }

    function save($data): void {
        if (is_array($data)) {
            for ($x = 0; $x < count($data); ++$x) {
                try {
                    $this->entityManager->persist($data[$x]);

                    if ((($x+1) % $this->batchSize) === 0) {

                        $this->entityManager->flush();
                        $this->entityManager->clear();
                        echo "Saved {$this->batchSize} records.\n";
                    }
                } catch (Exception $ex) {
                    echo "failed to save $this->batchSize records\n";
                    // After failing to load data to db the EntityManager closes.
                    // Here we are trying to reset it.
                    $this->resetEntityManager();
                }
            }
            // For the remaining data records
            try {
                $this->entityManager->flush();
                $this->entityManager->clear();
            } catch (Exception $ex) {
                $this->resetEntityManager();
                echo "failed to save remaining records\n";
            }
        } else {
            echo "Insert an array of Object(s)";
        }
    }

    private function resetEntityManager(): void{
        if (!($this->entityManager->isOpen())) {
            $this->entityManager = new EntityManager(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }
    }
}