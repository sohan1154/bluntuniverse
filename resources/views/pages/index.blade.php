@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    
    <div class="card-header py-3">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h6 class="m-0 font-weight-bold text-primary">{{$title}}</h6>
            </div>
            
            <div class="col-sm-12 col-md-6  text-align-right">
                <a href="{{ route('page-add') }}" title="Add New Record" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Add New</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">

        <!-- listing table start -->
        <div id="listRecords" class="table-responsive">
            @include('pages.partials.listing')
        </div>
        <!-- end listing table -->

    </div>

</div>

@stop