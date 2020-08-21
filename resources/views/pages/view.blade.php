@extends('layouts.admin')

@section('content')
            
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{$sub_title}}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
                
            <div class="form-group">

                <div class="col-sm-6 mb-3">
                    <strong>Title:</strong> {{$rowInfo->title}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Page Key:</strong> {{$rowInfo->page_key}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Description:</strong> {!! $rowInfo->description !!}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Meta Key:</strong> {{$rowInfo->meta_key}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Meta Description:</strong> {{$rowInfo->mea_description}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Status:</strong> {{ getStatus($rowInfo->status) }}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Created:</strong> {{ formatedDate($rowInfo->created_at) }}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Updated:</strong> {{ formatedDate($rowInfo->updated_at) }}
                </div>

            </div>

        </div>
    </div>
</div>

@stop