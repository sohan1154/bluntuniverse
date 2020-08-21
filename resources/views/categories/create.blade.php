@extends('layouts.admin')

@section('content')
            
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add/Edit Form</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <form id="addNew" class="addNew" action="{{ $url }}" enctype="multipart/form-data" method="post" autocomplete="off">
                  
                @csrf
                <input type="hidden" name="id" value="{{$rowInfo->id}}">
                
                <div class="form-group">

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control title-for-slug" name="name" placeholder="Name" value="{{old('name', $rowInfo->name)}}" required>
                        @if($errors->has('name'))
                        <small class="error-message">
                            {{$errors->first('name')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug (should be unique)" value="{{old('slug', $rowInfo->slug)}}" required>
                        @if($errors->has('slug'))
                        <small class="error-message">
                            {{$errors->first('slug')}}
                        </small>
                        @endif
                    </div>

                    <!-- <div class="col-sm-6 mb-3">
                        <input type="file" id="image" name="image" class="form-control"><br>
                        <?php if($rowInfo->id) { ?>
                        <span class="imageTag"><img src="{{ getImage($rowInfo->image, 'categories/'.$rowInfo->id.'/thumb') }}"></span>
                        <?php } ?>
                    </div>                     -->

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