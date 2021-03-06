<?php

namespace Laravel\Scout\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Laravel\Scout\Tests\Fixtures\SearchableTestModel;

class SearchableTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_searchable_using_update_is_called_on_collection()
    {
        $collection = m::mock();
        $collection->shouldReceive('isEmpty')->andReturn(false);
        $collection->shouldReceive('first->searchableUsing->update')->with($collection);

        $model = new SearchableTestModel;
        $model->queueMakeSearchable($collection);
    }

    public function test_searchable_using_update_is_not_called_on_empty_collection()
    {
        $collection = m::mock();
        $collection->shouldReceive('isEmpty')->andReturn(true);
        $collection->shouldNotReceive('first->searchableUsing->update');

        $model = new SearchableTestModel;
        $model->queueMakeSearchable($collection);
    }

    public function test_searchable_using_delete_is_called_on_collection()
    {
        $collection = m::mock();
        $collection->shouldReceive('isEmpty')->andReturn(false);
        $collection->shouldReceive('first->searchableUsing->delete')->with($collection);

        $model = new SearchableTestModel;
        $model->queueRemoveFromSearch($collection);
    }

    public function test_searchable_using_delete_is_not_called_on_empty_collection()
    {
        $collection = m::mock();
        $collection->shouldReceive('isEmpty')->andReturn(true);
        $collection->shouldNotReceive('first->searchableUsing->delete');

        $model = new SearchableTestModel;
        $model->queueRemoveFromSearch($collection);
    }

    public function test_make_all_searchable_uses_order_by()
    {
        ModelStubForMakeAllSearchable::makeAllSearchable();
    }
}

class ModelStubForMakeAllSearchable extends SearchableTestModel
{
    public function newQuery()
    {
        $mock = \Mockery::mock('Illuminate\Database\Eloquent\Builder');

        $mock->shouldReceive('orderBy')
            ->with('id')
            ->andReturnSelf()
            ->shouldReceive('searchable');

        $mock->shouldReceive('when')->andReturnSelf();

        return $mock;
    }
}

namespace Laravel\Scout;

function config($arg)
{
    return false;
}
