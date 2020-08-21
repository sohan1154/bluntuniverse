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
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                
                <div class="form-group">
                    
                    <div class="col-sm-6 mb-3">
                        <select name="image_type" id="image_type"  class="form-control" required>
                            <option value="">Please select</option>
                            <?php foreach($imageTypes as $key=>$value) { ?>
                                <option value="<?php echo $key ?>" {{ $rowInfo->image_type==$key ? 'selected' : '' }}><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{old('title', $rowInfo->title)}}" required>
                        @if($errors->has('title'))
                        <small class="error-message">
                            {{$errors->first('title')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="file" id="image" name="image" class="form-control"><br>
                        <?php if($rowInfo->id) { ?>
                        <span class="imageTag"><img src="{{ getImage($rowInfo->image, 'galleries/'.$rowInfo->id.'/thumb') }}"></span>
                        <?php } ?>
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