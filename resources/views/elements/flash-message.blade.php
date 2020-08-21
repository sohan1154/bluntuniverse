@if(Session::has('success'))
<div class="row" id="flash-message-success">
    <div class="col-lg-12 mb-12">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <div class="small">{{ Session::get('success') }}</div>
            </div>
        </div>
    </div>
</div>
@elseif(Session::has('error'))
<div class="row" id="flash-message-error">
    <div class="col-lg-12 mb-12">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                <div class="small">{{ Session::get('error') }}</div>
            </div>
        </div>
    </div>
</div>
@elseif(Session::has('warning'))
<div class="row" id="flash-message-warning">
    <div class="col-lg-12 mb-12">
        <div class="card bg-warning text-white shadow">
            <div class="card-body">
                <div class="small">{{ Session::get('warning') }}</div>
            </div>
        </div>
    </div>
</div>
@endif     
