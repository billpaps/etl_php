<?php

abstract class AbstractPipeline implements DataPipelineInterface
{
    protected array $opts;
    private DatabaseWorker $worker;

    public function __construct(array $opts, DatabaseWorker $worker)
    {
        $this->opts = $opts;
        $this->worker = $worker;
    }

    public function execute(): void
    {
        $raw_data = $this->extract($this->opts);
        $data = $this->transform($raw_data, $this->opts);
        $this->worker->save($data);
    }
}