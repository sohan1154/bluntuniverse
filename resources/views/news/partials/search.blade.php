<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

    <form action="{{$url}}" method="post" id="recordSearch">
        <div class="row">
            <!-- <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="dataTable_length">
                    
                </div>
            </div> -->
            <div class="col-sm-12 col-md-12">
                <div id="dataTable_filter" class="dataTables_filter">
                    <label>
                        <select name="is_verified" id="is_verified" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm">
                            <option value="">All</option>
                            <option value="1">Verified</option>
                            <option value="0">Un-Verified</option>
                        </select>
                    </label>
                    <label>
                        <select name="status" id="status" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </label>
                    <label>
                        <input type="search Keyword" name="search" id="search" class="form-control form-control-sm" placeholder="Search" aria-controls="dataTable">
                    </label>
                </div>
            </div>
        </div>
    </form>

</div>
