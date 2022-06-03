@extends('master.admin')

@section('admin-content')
    <div class="row">
        <div class="col">
            <h2>ElasticSearch index state</h2>
            <hr>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <p class="alert alert-danger">Warning! Do this only once! It will delete all Product indexes and Reindex all! Do not close the tab! This might take long time depending on products count.</p>
            <p><a class="btn btn-danger btn-lg" href="{{ route('admin.elasticsearch.ReindexAll') }}" target="_blank">ElasticSearch Reindex Products</a></p>
    <hr>
        </div>

    </div>

    <div class="row">


        <div class="col-md-4">
            <h4>All Products count</h4>
            <p><strong> {{$total_products}} </strong></p>
        </div>

        <div class="col-md-4">
            <h4>Active Products count</h4>
            <p><strong> {{$active_products}}</strong></p>
        </div>

        <div class="col-md-4">
            <h4>Inactive Products count</h4>
            <p><strong> {{$inactive_products}}</strong></p>
        </div>


    </div>

    <div class="row">

        <div class="col-md-12">
            <br/>
            <br/>
            <br/>
            <br/>
            <h3>ElasticSearch Index State details</h3>
            <p>Realtime data.</p>

            <table class="table">
                <tbody>

                <tr>
                    <td>Indexed products</td>
                    <td><strong>{{ $elasticsearch['indices'][env('ELASTICSEARCH_INDEX')]['total']['docs']['count'] }}</strong></td>
                </tr>

                </tbody>
            </table>

        </div>

    </div>


@stop
