@extends('layouts.admin')

@section('content')
            
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <form id="updateProfile" class="updateProfile" action="{{ $url }}" enctype="multipart/form-data" method="post" autocomplete="off">
                  
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}">
                
                <div class="form-group">

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name" value="{{old('name', $user->name)}}" required>
                        @if($errors->has('name'))
                        <small class="error-message">
                            {{$errors->first('name')}}
                        </span>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email', $user->email)}}" <?=($user->id)?'readonly':''?> required>
                        @if($errors->has('email'))
                        <small class="error-message">
                            {{$errors->first('email')}}
                        </span>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="mobile" placeholder="Mobile" value="{{old('mobile', $user->mobile)}}" required>
                        @if($errors->has('mobile'))
                        <small class="error-message">
                            {{$errors->first('mobile')}}
                        </span>
                        @endif
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                        <input type="file" id="image" name="image" class="form-control"><br>
                        <?php if($user->id) { ?>
                        <span class="imageTag"><img src="{{ getImage($user->image, getRoleBasedImageDirectory().'/'.$user->id.'/medium') }}"></span>
                        <?php } ?>
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