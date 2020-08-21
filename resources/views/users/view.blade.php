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
                    <strong>Name:</strong> {{$rowInfo->name}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Email:</strong> {{$rowInfo->email}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Mobile:</strong> {{$rowInfo->mobile}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Address:</strong> {{$rowInfo->address}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Account Status:</strong> {{ getStatus($rowInfo->status) }}
                </div>


                <div class="col-sm-6 mb-3">
                    <strong>Image</strong><br>
                    <span class="imageTag"><img style="max-width: 400px;" src="{{ getImage($rowInfo->image, 'users/'.$rowInfo->id) }}"></span>
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