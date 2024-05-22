## Implementation

I created an abstact class AbstractPipeline and an interface class DataPipelineInterface. The abstract class implements the interface.
Every pipeline must implement the abstact class behaviour and its' callbacks (extract, transform). Also each pipeline muyst be configured
with the worker which will load the data to the database.

Also there is an `DatabaseWorker` interface class where database specific class should implement. The `Sqlite` class does that.