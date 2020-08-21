@extends('layouts.admin')

@section('content')
            
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add/Edit Form</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <form id="addNew" class="addNew" action="{{ $url }}" method="post" autocomplete="off">
                  
                @csrf
                <input type="hidden" name="id" value="{{$rowInfo->id}}">
                
                <div class="form-group">

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{old('title', $rowInfo->title)}}" required>
                        @if($errors->has('title'))
                        <small class="error-message">
                            {{$errors->first('title')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="page_key" placeholder="Page Key" value="{{old('page_key', $rowInfo->page_key)}}" required>
                        @if($errors->has('page_key'))
                        <small class="error-message">
                            {{$errors->first('page_key')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-10 mb-3">
                        <textarea class="form-control" id="ckeditor" name="description" placeholder="Description" required>{{old('description', $rowInfo->description)}}</textarea>
                        @if($errors->has('description'))
                        <small class="error-message">
                            {{$errors->first('description')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-10 mb-3">
                        <input type="text" class="form-control" name="meta_key" placeholder="Meta Key" value="{{old('meta_key', $rowInfo->meta_key)}}" >
                        @if($errors->has('meta_key'))
                        <small class="error-message">
                            {{$errors->first('meta_key')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-10 mb-3">
                        <textarea class="form-control" name="meta_description" placeholder="Meta Description">{{old('meta_description', $rowInfo->meta_description)}}</textarea>
                        @if($errors->has('meta_description'))
                        <small class="error-message">
                            {{$errors->first('meta_description')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <select id="status" name="status" class="form-control"  required>
                            <option value="0" {{ $rowInfo->status==0 ? 'selected' : '' }}>Disable</option>
                            <option value="1" {{ $rowInfo->status==1 || $rowInfo->id==null ? 'selected' : '' }}>Enable</option>
                        </select>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <button type="submit" class="btn btn-primary btn-user btn-block" id="formSubmit">Save</button>
                    </div>
                </div>
              </form>

        </div>
    </div>
</div>

@stop