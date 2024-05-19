<?php

interface DataPipelineInterface
{
    // Fetch data from a specific source
    public function extract($opts);

    // Transform data
    public function transform(array $data, array $opts);
}
